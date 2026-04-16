<?php

class FundraiserModeration extends Controller
{
    private function requireSuperAdmin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = (int)($_SESSION['user_id'] ?? 0);
        $role = strtolower((string)($_SESSION['role'] ?? ''));
        if ($userId <= 0 || $role !== 'super_admin') {
            redirect('superadmin');
        }

        return $userId;
    }

    public function index()
    {
        $this->requireSuperAdmin();
        $fundraiserModel = new Fundraiser();
        $stats = $fundraiserModel->getAdminStats(null);
        $pendingFundraisers = $fundraiserModel->getPendingForFacultyAdmin(null);

        foreach ($pendingFundraisers as $fundraiser) {
            $fundraiser->documents = $fundraiserModel->getDocuments((int)$fundraiser->fundraiser_id);
        }

        $data = [
            'title' => 'Moderate Fundraisers - GradBridge',
            'page_title' => 'Moderate Fundraisers',
            'page_subtitle' => 'Review pending fundraiser requests and decide approvals.',
            'user' => $_SESSION,
            'fundraiserData' => [
                'pending_fundraisers' => $pendingFundraisers,
                'stats' => [
                    'total_fundraisers' => (int)($stats->total_fundraisers ?? 0),
                    'pending_fundraisers' => (int)($stats->pending_fundraisers ?? 0),
                    'approved_fundraisers' => (int)($stats->approved_fundraisers ?? 0),
                ],
            ],
            'flash' => $_SESSION['flash_message'] ?? null,
        ];

        $this->view('superadmin/fundraiser-moderation', $data);
        unset($_SESSION['flash_message']);
    }

    public function approve($fundraiserId = null)
    {
        $adminUserId = $this->requireSuperAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$fundraiserId || !is_numeric($fundraiserId)) {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Invalid approve request'];
            redirect('superadmin/fundraiser-moderation');
        }

        $note = trim((string)($_POST['admin_note'] ?? ''));
        $fundraiserModel = new Fundraiser();
        $item = $fundraiserModel->getByIdWithStats((int)$fundraiserId);
        $ok = $fundraiserModel->approve((int)$fundraiserId, $adminUserId, $note);

        if ($ok && $item) {
            $notification = new Notification();
            $notification->insert([
                'recipient_user_id' => (int)$item->creator_user_id,
                'actor_user_id' => $adminUserId,
                'title' => 'Fundraiser request approved',
                'message' => 'Your fundraiser "' . $item->title . '" has been approved and is now live.',
                'is_read' => 0,
                'read_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Fundraiser approved'];
        } else {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Unable to approve fundraiser'];
        }

        redirect('superadmin/fundraiser-moderation');
    }

    public function reject($fundraiserId = null)
    {
        $adminUserId = $this->requireSuperAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$fundraiserId || !is_numeric($fundraiserId)) {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Invalid reject request'];
            redirect('superadmin/fundraiser-moderation');
        }

        $note = trim((string)($_POST['admin_note'] ?? ''));
        if ($note === '') {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Please provide a rejection note'];
            redirect('superadmin/fundraiser-moderation');
        }

        $fundraiserModel = new Fundraiser();
        $item = $fundraiserModel->getByIdWithStats((int)$fundraiserId);
        $ok = $fundraiserModel->reject((int)$fundraiserId, $adminUserId, $note);

        if ($ok && $item) {
            $notification = new Notification();
            $notification->insert([
                'recipient_user_id' => (int)$item->creator_user_id,
                'actor_user_id' => $adminUserId,
                'title' => 'Fundraiser request rejected',
                'message' => 'Your fundraiser "' . $item->title . '" was rejected. Note: ' . $note,
                'is_read' => 0,
                'read_at' => null,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Fundraiser rejected with note'];
        } else {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Unable to reject fundraiser'];
        }

        redirect('superadmin/fundraiser-moderation');
    }

    public function download($documentId = null)
    {
        $this->requireSuperAdmin();
        if (!$documentId || !is_numeric($documentId)) {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Invalid document request'];
            redirect('superadmin/fundraiser-moderation');
        }

        $fundraiserModel = new Fundraiser();
        $document = $fundraiserModel->getDocumentForModeration((int)$documentId, null);

        if (!$document) {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Document not found'];
            redirect('superadmin/fundraiser-moderation');
        }

        $relativePath = ltrim((string)$document->file_path, '/\\');
        $absolutePath = APPROOT . '/public/' . str_replace(['..\\', '../'], '', $relativePath);

        if (!is_file($absolutePath)) {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'File is missing on server'];
            redirect('superadmin/fundraiser-moderation');
        }

        $downloadName = trim((string)($document->original_name ?? 'fundraiser-document'));
        if ($downloadName === '') {
            $downloadName = 'fundraiser-document';
        }

        $mimeType = trim((string)($document->mime_type ?? 'application/octet-stream'));
        if ($mimeType === '') {
            $mimeType = 'application/octet-stream';
        }

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . rawurlencode($downloadName) . '"');
        header('Content-Length: ' . (string)filesize($absolutePath));
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: public');
        readfile($absolutePath);
        exit;
    }
}
