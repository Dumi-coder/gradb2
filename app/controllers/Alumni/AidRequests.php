<?php
class AidRequests extends Controller
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

        // Get fresh profile data from database
        $alumni = new Alumni();
        $profile = $alumni->getalumniProfile($_SESSION['alumni_id']);
        
        if (!$profile) {
            // If profile not found, logout and redirect
            session_destroy();
            redirect('alumni/auth');
        }

        // Get aid requests data (you can expand this to get real data)
        $aidRequestsData = $this->getAidRequestsData();

        $data = [
            'title' => 'Aid Requests - GradBridge',
            'profile' => $profile,
            'aidRequestsData' => $aidRequestsData,
            'user' => $_SESSION
        ];

        $this->view('alumni/aid-requests', $data);
    }

    private function getAidRequestsData()
    {
        $requestModel = new Request();

        $pendingRows = $requestModel->getAidRequestsForCounselorByStatuses(['open']);
        $approvedRows = $requestModel->getAidRequestsForCounselorByStatuses(['approved', 'accepted']);

        $formatAmount = static function ($amount) {
            if ($amount === null || $amount === '') {
                return null;
            }

            return 'LKR ' . number_format((float)$amount, 2);
        };

        $pending = array_map(function ($row) use ($formatAmount) {
            return [
                'id' => $row->request_id ?? null,
                'student_name' => $row->student_name ?? 'Student',
                'request_type' => ucfirst((string)($row->aid_type ?? 'Aid Request')),
                'description' => $row->reason ?? 'No description provided',
                'amount_requested' => $formatAmount($row->amount ?? null),
                'aid_type' => ucfirst((string)($row->aid_type ?? 'Aid')),
                'status' => 'pending',
            ];
        }, is_array($pendingRows) ? $pendingRows : []);

        $approved = array_map(function ($row) use ($formatAmount) {
            return [
                'id' => $row->request_id ?? null,
                'student_name' => $row->student_name ?? 'Student',
                'request_type' => ucfirst((string)($row->aid_type ?? 'Aid Request')),
                'description' => $row->reason ?? 'No description provided',
                'provided_value' => $formatAmount($row->amount ?? null) ?? 'N/A',
                'aid_type' => ucfirst((string)($row->aid_type ?? 'Aid')),
                'status' => 'approved',
            ];
        }, is_array($approvedRows) ? $approvedRows : []);

        return [
            'pending' => $pending,
            'approved' => $approved,
            'completed' => [],
        ];
    }
}
