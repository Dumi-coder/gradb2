<?php
class Dashboard extends Controller
{    
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'alumni') {
            redirect('alumni/auth');
        }

        // Prevent caching of dashboard pages
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // Get fresh profile data from database
        $alumni = new Alumni();
        $profile = $alumni->getalumniProfile($_SESSION['alumni_id']);
        
        if (!$profile) {
            // If profile not found, logout and redirect
            session_destroy();
            redirect('alumni/auth');
        }

        // Get dashboard statistics and chart-ready engagement insights.
        $stats = $this->getDashboardStats();
        $mentorshipData = $this->getMentorshipData();
        $aidPreview = $this->getAidPreview();
        $fundraiserPreview = $this->getFundraiserPreview();

        $metrics = $this->getAlumniContributionMetrics($mentorshipData);
        $profileSnapshot = $metrics['profileSnapshot'];
        $engagementChartData = $metrics['engagementChartData'];

        $eventModel = new Event();
        $events = $eventModel->getAllActiveEvents();
        $eventPreview = is_array($events) ? array_slice($events, 0, 3) : [];

        $forumModel = new ForumPost();
        $forumPosts = $forumModel->getPostsByFaculty((int)($profile->faculty_id ?? 0));
        $forumPreview = is_array($forumPosts) ? array_slice($forumPosts, 0, 3) : [];

        $notificationModel = new Notification();
        $notificationRows = $notificationModel->query(
            "SELECT notification_id, title, message, action_url, action_label, created_at
             FROM notifications
             WHERE recipient_user_id = :user_id AND is_read = 0
             ORDER BY created_at DESC
             LIMIT 5",
            ['user_id' => (int)$_SESSION['user_id']]
        );
        $notificationPreview = is_array($notificationRows) ? $notificationRows : [];

        $badges = $this->buildAlumniBadges($profileSnapshot, $stats, $engagementChartData);

        $quickLinks = [
            ['title' => 'Mentorship', 'icon' => 'fa-user-graduate', 'url' => ROOT . '/alumni/mentorship', 'hint' => 'Respond to student mentorship requests'],
            ['title' => 'Aid Requests', 'icon' => 'fa-hands-helping', 'url' => ROOT . '/alumni/aidrequests', 'hint' => 'Support active aid requests'],
            ['title' => 'Discussion Forum', 'icon' => 'fa-comments', 'url' => ROOT . '/alumni/discussionforum', 'hint' => 'Share guidance with students'],
            ['title' => 'Events Board', 'icon' => 'fa-calendar-days', 'url' => ROOT . '/alumni/eventboard', 'hint' => 'Join or host community events'],
            ['title' => 'Resources', 'icon' => 'fa-folder-open', 'url' => ROOT . '/alumni/resources', 'hint' => 'Upload practical resources'],
            ['title' => 'Fundraising', 'icon' => 'fa-hand-holding-heart', 'url' => ROOT . '/alumni/fundraising', 'hint' => 'Support live campaigns'],
        ];

        $data = [
            'title' => 'Alumni Dashboard - GradBridge',
            'profile' => $profile,
            'stats' => $stats,
            'mentorshipData' => $mentorshipData,
            'aidPreview' => $aidPreview,
            'fundraiserPreview' => $fundraiserPreview,
            'profileSnapshot' => $profileSnapshot,
            'engagementChartData' => $engagementChartData,
            'eventPreview' => $eventPreview,
            'forumPreview' => $forumPreview,
            'notificationPreview' => $notificationPreview,
            'quickLinks' => $quickLinks,
            'badges' => $badges,
            'user' => $_SESSION,
        ];

        $this->view('alumni/dashboard', $data);
    }

    private function getDashboardStats()
    {
        $userId = (int)$_SESSION['user_id'];

        $requestModel = new Request();
        $eventRegistrationModel = new EventRegistration();
        $fundraiserDonationModel = new FundraiserDonation();
        $mentorshipRequestModel = new MentorshipRequest();

        $activeMentorships = $mentorshipRequestModel->getActiveMentorshipsForMentor($userId);

        $aidCompletedRow = $requestModel->get_row(
            "SELECT COUNT(*) AS total
             FROM requests
             WHERE request_type = 'aid'
               AND alumnus_user_id = :user_id
               AND status = 'completed'",
            ['user_id' => $userId]
        );

        $eventCountRow = $eventRegistrationModel->get_row(
            "SELECT COUNT(*) AS total
             FROM event_registrations
             WHERE registered_alumni_id = :user_id",
            ['user_id' => $userId]
        );

        $totalDonationRow = $fundraiserDonationModel->get_row(
            "SELECT COALESCE(SUM(amount), 0) AS total
             FROM fundraiser_donations
             WHERE donor_user_id = :user_id
               AND status = 'captured'",
            ['user_id' => $userId]
        );

        return [
            'mentorship_requests' => is_array($activeMentorships) ? count($activeMentorships) : 0,
            'aid_requests' => (int)($aidCompletedRow->total ?? 0),
            'events_attended' => (int)($eventCountRow->total ?? 0),
            'total_donations' => (float)($totalDonationRow->total ?? 0),
        ];
    }
    
    private function getMentorshipData()
    {
        $mentorshipRequestModel = new MentorshipRequest();
        $alumnusId = $_SESSION['user_id'];
        
        // Get directed pending mentorship requests sent to this alumnus (limit to 1 for dashboard)
        $pendingRequests = $mentorshipRequestModel->getRequestsForAlumnusFaculty($alumnusId);
        
        // Keep dashboard concise but meaningful.
        $limitedRequests = is_array($pendingRequests) ? array_slice($pendingRequests, 0, 3) : [];
        
        // Format pending requests for display
        $formattedPendingRequests = [];
        foreach ($limitedRequests as $request) {
            $formattedPendingRequests[] = [
                'id' => $request['request_id'],
                'student_name' => $request['student_name'],
                'student_email' => $request['student_email'],
                'student_id' => $request['student_id'],
                'academic_year' => $request['academic_year'],
                'faculty_name' => $request['faculty_name'],
                'guidance_type' => $request['mentorship_category'] === 'other'
                    ? ($request['other_category'] ?: 'General Mentorship')
                    : $request['mentorship_category'],
                'description' => $request['request_reason'],
                'status' => 'pending',
                'created_at' => $request['created_at']
            ];
        }

        $activeMentorships = $mentorshipRequestModel->getActiveMentorshipsForMentor((int)$alumnusId);
        $completedMentorships = $mentorshipRequestModel->getCompletedMentorshipsForMentor((int)$alumnusId);
        $reputation = $mentorshipRequestModel->getMentorReputation((int)$alumnusId);
        
        return [
            'requests' => $formattedPendingRequests,
            'active' => is_array($activeMentorships) ? $activeMentorships : [],
            'completed' => is_array($completedMentorships) ? $completedMentorships : [],
            'reputation' => is_array($reputation) ? $reputation : [],
        ];
    }

    private function getAidPreview()
    {
        $requestModel = new Request();
        $rows = $requestModel->query(
            "SELECT r.request_id, r.status, r.created_at,
                    ar.aid_type, ar.amount,
                    u.name AS student_name
             FROM requests r
             LEFT JOIN aid_requests ar ON ar.request_id = r.request_id
             LEFT JOIN users u ON u.user_id = r.student_user_id
             WHERE r.request_type = 'aid'
               AND r.alumnus_user_id = :user_id
             ORDER BY r.created_at DESC
             LIMIT 3",
            ['user_id' => (int)$_SESSION['user_id']]
        );

        return is_array($rows) ? $rows : [];
    }

    private function getFundraiserPreview()
    {
        $fundraiserModel = new Fundraiser();
        $rows = $fundraiserModel->getApprovedForFeed((int)$_SESSION['user_id']);
        return is_array($rows) ? array_slice($rows, 0, 3) : [];
    }

    private function getAlumniContributionMetrics(array $mentorshipData)
    {
        $userId = (int)$_SESSION['user_id'];

        $sharedResourceModel = new SharedResource();
        $forumPostModel = new ForumPost();
        $forumReplyModel = new ForumReply();
        $fundraiserDonationModel = new FundraiserDonation();
        $requestModel = new Request();

        $resourceCountRow = $sharedResourceModel->get_row(
            "SELECT COUNT(*) AS total FROM resources WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        $resourceContributionCount = (int)($resourceCountRow->total ?? 0);

        $forumPostCountRow = $forumPostModel->get_row(
            "SELECT COUNT(*) AS total FROM forum_posts WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        $forumPostCount = (int)($forumPostCountRow->total ?? 0);

        $forumReplyCountRow = $forumReplyModel->get_row(
            "SELECT COUNT(*) AS total FROM form_replies WHERE userid = :user_id",
            ['user_id' => $userId]
        );
        $forumReplyCount = (int)($forumReplyCountRow->total ?? 0);

        $fundContributionCountRow = $fundraiserDonationModel->get_row(
            "SELECT COUNT(*) AS total
             FROM fundraiser_donations
             WHERE donor_user_id = :user_id
               AND status = 'captured'",
            ['user_id' => $userId]
        );
        $fundContributionCount = (int)($fundContributionCountRow->total ?? 0);

        $fundContributionAmountRow = $fundraiserDonationModel->get_row(
            "SELECT COALESCE(SUM(amount), 0) AS total
             FROM fundraiser_donations
             WHERE donor_user_id = :user_id
               AND status = 'captured'",
            ['user_id' => $userId]
        );
        $fundContributionAmount = (float)($fundContributionAmountRow->total ?? 0);

        $timelineDayKeys = [];
        $timelineLabels = [];
        for ($i = 29; $i >= 0; $i--) {
            $dayKey = date('Y-m-d', strtotime('-' . $i . ' days'));
            $timelineDayKeys[] = $dayKey;
            $timelineLabels[] = date('M j', strtotime($dayKey));
        }

        $emptyTimelineMap = array_fill_keys($timelineDayKeys, 0);

        $resourcesTimelineMap = $emptyTimelineMap;
        $resourceRows = $sharedResourceModel->query(
            "SELECT created_at FROM resources WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        if (is_array($resourceRows)) {
            foreach ($resourceRows as $row) {
                $ts = strtotime((string)($row->created_at ?? ''));
                if ($ts !== false) {
                    $key = date('Y-m-d', $ts);
                    if (array_key_exists($key, $resourcesTimelineMap)) {
                        $resourcesTimelineMap[$key]++;
                    }
                }
            }
        }

        $forumPostsTimelineMap = $emptyTimelineMap;
        $forumPostRows = $forumPostModel->query(
            "SELECT created_at FROM forum_posts WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        if (is_array($forumPostRows)) {
            foreach ($forumPostRows as $row) {
                $ts = strtotime((string)($row->created_at ?? ''));
                if ($ts !== false) {
                    $key = date('Y-m-d', $ts);
                    if (array_key_exists($key, $forumPostsTimelineMap)) {
                        $forumPostsTimelineMap[$key]++;
                    }
                }
            }
        }

        $forumRepliesTimelineMap = $emptyTimelineMap;
        $forumReplyRows = $forumReplyModel->query(
            "SELECT repliedtime AS activity_time FROM form_replies WHERE userid = :user_id",
            ['user_id' => $userId]
        );
        if (is_array($forumReplyRows)) {
            foreach ($forumReplyRows as $row) {
                $ts = strtotime((string)($row->activity_time ?? ''));
                if ($ts !== false) {
                    $key = date('Y-m-d', $ts);
                    if (array_key_exists($key, $forumRepliesTimelineMap)) {
                        $forumRepliesTimelineMap[$key]++;
                    }
                }
            }
        }

        $mentorshipCompletedTimelineMap = $emptyTimelineMap;
        $activeMentorshipCount = is_array($mentorshipData['active'] ?? null) ? count($mentorshipData['active']) : 0;
        $completedMentorshipCount = is_array($mentorshipData['completed'] ?? null) ? count($mentorshipData['completed']) : 0;
        if (is_array($mentorshipData['completed'] ?? null)) {
            foreach ($mentorshipData['completed'] as $session) {
                $ts = strtotime((string)($session['reviewed_at'] ?? $session['created_at'] ?? ''));
                if ($ts !== false) {
                    $key = date('Y-m-d', $ts);
                    if (array_key_exists($key, $mentorshipCompletedTimelineMap)) {
                        $mentorshipCompletedTimelineMap[$key]++;
                    }
                }
            }
        }

        $aidCompletedTimelineMap = $emptyTimelineMap;
        $aidCompletedCount = 0;
        $aidRows = $requestModel->query(
            "SELECT status, created_at AS activity_time
             FROM requests
             WHERE request_type = 'aid'
               AND alumnus_user_id = :user_id",
            ['user_id' => $userId]
        );
        if (is_array($aidRows)) {
            foreach ($aidRows as $aid) {
            $status = strtolower((string)($aid->status ?? ''));
            if ($status === 'completed') {
                $aidCompletedCount++;
            }

            if (in_array($status, ['approved', 'accepted', 'completed'], true)) {
                $ts = strtotime((string)($aid->activity_time ?? ''));
                if ($ts !== false) {
                    $key = date('Y-m-d', $ts);
                    if (array_key_exists($key, $aidCompletedTimelineMap)) {
                        $aidCompletedTimelineMap[$key]++;
                    }
                }
            }
            }
        }

        $fundTimelineMap = $emptyTimelineMap;
        $fundRows = $fundraiserDonationModel->query(
            "SELECT COALESCE(captured_at, created_at) AS activity_time
             FROM fundraiser_donations
             WHERE donor_user_id = :user_id
               AND status = 'captured'",
            ['user_id' => $userId]
        );
        if (is_array($fundRows)) {
            foreach ($fundRows as $row) {
                $ts = strtotime((string)($row->activity_time ?? ''));
                if ($ts !== false) {
                    $key = date('Y-m-d', $ts);
                    if (array_key_exists($key, $fundTimelineMap)) {
                        $fundTimelineMap[$key]++;
                    }
                }
            }
        }

        $dailyActivityTotals = [];
        foreach ($timelineDayKeys as $dayKey) {
            $dailyActivityTotals[] =
                (int)($resourcesTimelineMap[$dayKey] ?? 0) +
                (int)($forumPostsTimelineMap[$dayKey] ?? 0) +
                (int)($forumRepliesTimelineMap[$dayKey] ?? 0) +
                (int)($fundTimelineMap[$dayKey] ?? 0) +
                (int)($mentorshipCompletedTimelineMap[$dayKey] ?? 0) +
                (int)($aidCompletedTimelineMap[$dayKey] ?? 0);
        }

        $communityTotal = $resourceContributionCount + $forumPostCount + $forumReplyCount + $fundContributionCount;
        $impactTotal = $activeMentorshipCount + $completedMentorshipCount + $aidCompletedCount;

        return [
            'profileSnapshot' => [
                'active_mentorships' => $activeMentorshipCount,
                'completed_mentorships' => $completedMentorshipCount,
                'aid_facilitated' => $aidCompletedCount,
                'resources_shared' => $resourceContributionCount,
                'forum_contributions' => $forumPostCount + $forumReplyCount,
                'donations_made' => $fundContributionCount,
                'donations_amount' => $fundContributionAmount,
            ],
            'engagementChartData' => [
                'activity_timeline' => [
                    'labels' => $timelineLabels,
                    'values' => $dailyActivityTotals,
                ],
                'units_mix' => [
                    'labels' => ['Mentorships Given', 'Aid Facilitated', 'Resources Shared', 'Forum Contributions', 'Donations'],
                    'values' => [
                        $activeMentorshipCount + $completedMentorshipCount,
                        $aidCompletedCount,
                        $resourceContributionCount,
                        $forumPostCount + $forumReplyCount,
                        $fundContributionCount,
                    ],
                ],
                'community_total' => $communityTotal,
                'impact_total' => $impactTotal,
            ],
        ];
    }

    private function buildAlumniBadges(array $profileSnapshot, array $stats, array $engagementChartData)
    {
        $badgeDefinitions = [
            [
                'key' => 'first_response',
                'title' => 'First Response',
                'description' => 'Accept your first mentorship request.',
                'icon' => 'fa-handshake',
                'value' => (int)($profileSnapshot['active_mentorships'] ?? 0) + (int)($profileSnapshot['completed_mentorships'] ?? 0),
                'target' => 1,
            ],
            [
                'key' => 'mentor_anchor',
                'title' => 'Mentor Anchor',
                'description' => 'Complete 5 mentorship sessions.',
                'icon' => 'fa-user-tie',
                'value' => (int)($profileSnapshot['completed_mentorships'] ?? 0),
                'target' => 5,
            ],
            [
                'key' => 'resource_catalyst',
                'title' => 'Resource Catalyst',
                'description' => 'Share 5 resources with students.',
                'icon' => 'fa-folder-open',
                'value' => (int)($profileSnapshot['resources_shared'] ?? 0),
                'target' => 5,
            ],
            [
                'key' => 'forum_guide',
                'title' => 'Forum Guide',
                'description' => 'Contribute 10 forum posts/replies.',
                'icon' => 'fa-comments',
                'value' => (int)($profileSnapshot['forum_contributions'] ?? 0),
                'target' => 10,
            ],
            [
                'key' => 'community_pulse',
                'title' => 'Community Pulse',
                'description' => 'Reach 20 total contribution units.',
                'icon' => 'fa-chart-line',
                'value' => (int)($engagementChartData['community_total'] ?? 0),
                'target' => 20,
            ],
        ];

        $badges = [];
        foreach ($badgeDefinitions as $badge) {
            $value = (float)$badge['value'];
            $target = (float)$badge['target'];
            $progressPct = $target > 0 ? min(100, (int)round(($value / $target) * 100)) : 0;
            $isEarned = $value >= $target;

            $badges[] = [
                'key' => $badge['key'],
                'title' => $badge['title'],
                'description' => $badge['description'],
                'icon' => $badge['icon'],
                'value' => $value,
                'target' => $target,
                'progress_pct' => $progressPct,
                'is_earned' => $isEarned,
            ];
        }

        return $badges;
    }

    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        redirect('home');
    }
}