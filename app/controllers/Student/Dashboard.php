<?php
// class Dashboard extends Controller
// {    
//     public function index()
//     {        
//         // $data['name'] = empty($_SESSION['USER']) ? 'User' : $_SESSION['USER']->name;

//         if (empty($_SESSION['USER'])) {
//             // guest student
//             $data['username'] = 'Guest';
//             // $data['user_id']       = null;
//             // $data['bio']      = 'No bio available';
//             // $data['mobile']   = 'N/A';
//         } else {
//             // logged-in user
//             $data['username'] = $_SESSION['USER']->name;
//             $data['userrole']       = $_SESSION['USER']->role;
//             $data['useremail']      = $_SESSION['USER']->email;
//             // $data['mobile']   = $_SESSION['USER']->mobile;
//         }
        


//         $this->view('student/dashboard', $data);
//     }
// }



class Dashboard extends Controller
{
    public function index()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            redirect('student/auth');
        }

        // Prevent caching of dashboard pages
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // Get fresh profile data from database
        $student = new Student();
        $profile = $student->getStudentProfile($_SESSION['student_id']);
        
        if (!$profile) {
            // If profile not found, logout and redirect
            session_destroy();
            redirect('student/auth');
        }

        // Get dashboard statistics (legacy fallback)
        $stats = $this->getDashboardStats();
        $flashMessage = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        $requestModel = new Request();
        $aidRequests = $requestModel->getAidRequestsForStudent($_SESSION['user_id']);
        $recentAidRequests = is_array($aidRequests) ? array_slice($aidRequests, 0, 3) : [];

        $mentorshipModel = new MentorshipRequest();
        $mentorshipRequests = $mentorshipModel->getStudentMentorshipRequests($_SESSION['user_id']);

        $activeMentorshipCount = 0;
        $completedMentorshipCount = 0;
        if (is_array($mentorshipRequests)) {
            foreach ($mentorshipRequests as $request) {
                $status = strtolower((string)($request['status'] ?? ''));
                if ($status === 'accepted') {
                    $activeMentorshipCount++;
                }
                if ($status === 'completed') {
                    $completedMentorshipCount++;
                }
            }
        }

        $sharedResourceModel = new SharedResource();
        $forumPostModel = new ForumPost();
        $forumReplyModel = new ForumReply();

        $resourceCountRow = $sharedResourceModel->get_row(
            "SELECT COUNT(*) AS total FROM resources WHERE user_id = :user_id",
            ['user_id' => (int)$_SESSION['user_id']]
        );
        $resourceContributionCount = (int)($resourceCountRow->total ?? 0);

        $forumPostCountRow = $forumPostModel->get_row(
            "SELECT COUNT(*) AS total FROM forum_posts WHERE user_id = :user_id",
            ['user_id' => (int)$_SESSION['user_id']]
        );
        $forumPostCount = (int)($forumPostCountRow->total ?? 0);

        $forumReplyCountRow = $forumReplyModel->get_row(
            "SELECT COUNT(*) AS total FROM form_replies WHERE userid = :user_id",
            ['user_id' => (int)$_SESSION['user_id']]
        );
        $forumReplyCount = (int)($forumReplyCountRow->total ?? 0);

        $totalCommunityContributions = $resourceContributionCount + $forumPostCount + $forumReplyCount;

        $aidStatusMix = [
            'pending' => 0,
            'accepted' => 0,
            'rejected' => 0,
            'other' => 0,
        ];

        $aidMonthlyMap = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthKey = date('Y-m', strtotime('-' . $i . ' months'));
            $aidMonthlyMap[$monthKey] = 0;
        }

        if (is_array($aidRequests)) {
            foreach ($aidRequests as $aid) {
                $status = strtolower((string)($aid->status ?? ''));
                if ($status === 'pending_verification') {
                    $aidStatusMix['pending']++;
                } elseif (in_array($status, ['open', 'approved', 'accepted'], true)) {
                    $aidStatusMix['accepted']++;
                } elseif ($status === 'rejected') {
                    $aidStatusMix['rejected']++;
                } else {
                    $aidStatusMix['other']++;
                }

                $createdAt = (string)($aid->created_at ?? '');
                $timestamp = strtotime($createdAt);
                if ($timestamp !== false) {
                    $monthKey = date('Y-m', $timestamp);
                    if (array_key_exists($monthKey, $aidMonthlyMap)) {
                        $aidMonthlyMap[$monthKey]++;
                    }
                }
            }
        }

        $profileSnapshot = [
            'active_mentorships' => $activeMentorshipCount,
            'completed_mentorships' => $completedMentorshipCount,
            'resources_shared' => $resourceContributionCount,
            'forum_contributions' => $forumPostCount + $forumReplyCount,
        ];

        $engagementChartData = [
            'overview_labels' => ['Mentorship Received', 'Resources Shared', 'Forum Posts', 'Forum Replies'],
            'overview_values' => [
                $completedMentorshipCount,
                $resourceContributionCount,
                $forumPostCount,
                $forumReplyCount,
            ],
            'contribution_mix' => [
                'resources' => $resourceContributionCount,
                'forum_posts' => $forumPostCount,
                'forum_replies' => $forumReplyCount,
            ],
            'community_total' => $totalCommunityContributions,
        ];

        $mentorshipPreview = [];
        if (is_array($mentorshipRequests)) {
            foreach (array_slice($mentorshipRequests, 0, 3) as $request) {
                $mentorshipPreview[] = [
                    'mentor_name' => (string)($request['mentor_name'] ?? 'Mentor'),
                    'topic' => (string)($request['topic'] ?? 'Mentorship'),
                    'status' => strtolower((string)($request['status'] ?? 'pending')),
                    'created_at' => (string)($request['created_at'] ?? ''),
                ];
            }
        }

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

        $quickLinks = [
            ['title' => 'Mentorship', 'icon' => 'fa-user-graduate', 'url' => ROOT . '/student/mentorship', 'hint' => 'Find mentors and manage requests'],
            ['title' => 'Aid Requests', 'icon' => 'fa-hand-holding-heart', 'url' => ROOT . '/student/aidrequests', 'hint' => 'Track your aid submissions'],
            ['title' => 'Discussion Forum', 'icon' => 'fa-comments', 'url' => ROOT . '/student/discussionforum', 'hint' => 'Join conversations'],
            ['title' => 'Events Board', 'icon' => 'fa-calendar-days', 'url' => ROOT . '/student/eventsboard', 'hint' => 'Register for upcoming events'],
            ['title' => 'Resources', 'icon' => 'fa-folder-open', 'url' => ROOT . '/student/resources', 'hint' => 'Browse shared materials'],
            ['title' => 'Fundraising', 'icon' => 'fa-coins', 'url' => ROOT . '/student/fundraising', 'hint' => 'Create and support campaigns'],
        ];

        $data = [
            'title' => 'Student Dashboard - GradBridge',
            'profile' => $profile,  // This contains all the data you need
            'stats' => $stats,
            'user' => $_SESSION,  // Session data if needed
            'flashMessage' => $flashMessage,
            'recentAidRequests' => $recentAidRequests,
            'profileSnapshot' => $profileSnapshot,
            'engagementChartData' => $engagementChartData,
            'mentorshipPreview' => $mentorshipPreview,
            'eventPreview' => $eventPreview,
            'forumPreview' => $forumPreview,
            'notificationPreview' => $notificationPreview,
            'quickLinks' => $quickLinks,
        ];

        $this->view('student/dashboard', $data);
    }

    private function getDashboardStats()
    {
        // You can expand this to get real statistics
        return [
            'total_requests' => 0,
            'pending_requests' => 0,
            'mentorship_connections' => 0,
            'events_attended' => 0
        ];
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