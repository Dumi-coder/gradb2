<?php
class Analytics extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'counselor') {
            redirect('counselor');
        }

        $requestModel = new Request();
        $days = 30;

        $summary = $requestModel->getAidAnalyticsSummary($days);
        $breakdown = $requestModel->getAidAnalyticsBreakdown($days);
        $recentRequests = $requestModel->getRecentAidRequestsForAnalytics(8);

        $this->view('counselor/analytics', [
            'summary' => $summary,
            'breakdown' => $breakdown,
            'recentRequests' => $recentRequests,
            'days' => $days,
        ]);
    }
}
