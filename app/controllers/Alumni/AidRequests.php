<?php
class AidRequests extends Controller
{
	private function ensureAuthenticatedAlumni()
	{
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'alumni') {
			redirect('alumni/auth');
		}
	}

    public function index()
    {
		$this->ensureAuthenticatedAlumni();

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

    public function approve($requestId = null)
    {
        $this->ensureAuthenticatedAlumni();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            redirect('alumni/AidRequests');
        }

        $requestId = (int)($requestId ?? 0);
        if ($requestId <= 0) {
            $_SESSION['error'] = 'Invalid request selected.';
            redirect('alumni/AidRequests');
        }

        $requestModel = new Request();
        $approved = $requestModel->approveAidRequestByAlumni($requestId, (int)($_SESSION['user_id'] ?? 0));

        if ($approved) {
            $_SESSION['success'] = 'Aid request approved successfully.';
        } else {
            $_SESSION['error'] = 'Unable to approve this request.';
        }

        redirect('alumni/AidRequests');
    }

    private function getAidRequestsData()
    {
        $requestModel = new Request();

        $pendingRows = $requestModel->getAidRequestsForCounselorByStatuses(['open']);
        $approvedRows = $requestModel->getAidRequestsForCounselorByStatuses(['approved', 'accepted']);
        $completedRows = $requestModel->getAidRequestsForCounselorByStatuses(['completed']);

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
                'provided_value' => $formatAmount($row->amount ?? null),
                'aid_type' => ucfirst((string)($row->aid_type ?? 'Aid')),
                'status' => 'approved',
            ];
        }, is_array($approvedRows) ? $approvedRows : []);

        $completed = array_map(function ($row) use ($formatAmount) {
            return [
                'id' => $row->request_id ?? null,
                'student_name' => $row->student_name ?? 'Student',
                'request_type' => ucfirst((string)($row->aid_type ?? 'Aid Request')),
                'description' => $row->reason ?? 'No description provided',
                'provided_value' => $formatAmount($row->amount ?? null),
                'aid_type' => ucfirst((string)($row->aid_type ?? 'Aid')),
                'completed_date' => $row->created_at ?? null,
                'status' => 'completed',
            ];
        }, is_array($completedRows) ? $completedRows : []);

        return [
            'pending' => $pending,
            'approved' => $approved,
            'completed' => $completed,
        ];
    }
}
