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
        $timeline = $requestModel->getAidAnalyticsTimeline($days);
        $statusMix = $requestModel->getAidAnalyticsStatusMix($days);

        $this->view('counselor/analytics', [
            'summary' => $summary,
            'timeline' => $timeline,
            'statusMix' => $statusMix,
            'days' => $days,
        ]);
    }
}
