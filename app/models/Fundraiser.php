
<?php

class Fundraiser
{
    use Model;

    private function normalizeMoneyValue($value)
    {
        $raw = trim((string)$value);
        if ($raw === '' || !preg_match('/^\d+(?:\.\d{1,2})?$/', $raw)) {
            return null;
        }

        $parts = explode('.', $raw, 2);
        $whole = ltrim($parts[0], '0');
        if ($whole === '') {
            $whole = '0';
        }

        $fraction = str_pad($parts[1] ?? '', 2, '0');
        return $whole . '.' . substr($fraction, 0, 2);
    }

    protected $table = 'fundraisers';
    protected $id_column = 'fundraiser_id';
    protected $order_column = 'created_at';
    protected $order_type = 'desc';

    protected $allowedColumns = [
        'creator_user_id',
        'title',
        'reasoning',
        'description',
        'target_amount',
        'currency',
        'status',
        'admin_note',
        'approved_by_user_id',
        'approved_at',
        'rejected_at',
        'created_at',
        'updated_at',
    ];

    public function createWithDocuments(array $fundraiserData, array $documents = [])
    {
        $con = $this->connect();
        $con->beginTransaction();
        $normalizedTargetAmount = $this->normalizeMoneyValue($fundraiserData['target_amount'] ?? '');

        if ($normalizedTargetAmount === null || (int)str_replace('.', '', $normalizedTargetAmount) < 100) {
            return ['success' => false, 'message' => 'Invalid target amount'];
        }

        try {
            $stmt = $con->prepare(
                "INSERT INTO fundraisers
                (creator_user_id, title, reasoning, description, target_amount, currency, status, created_at, updated_at)
                VALUES
                (:creator_user_id, :title, :reasoning, :description, :target_amount, :currency, :status, :created_at, :updated_at)"
            );

            $ok = $stmt->execute([
                'creator_user_id' => (int)$fundraiserData['creator_user_id'],
                'title' => (string)$fundraiserData['title'],
                'reasoning' => (string)$fundraiserData['reasoning'],
                'description' => (string)($fundraiserData['description'] ?? ''),
                'target_amount' => $normalizedTargetAmount,
                'currency' => strtoupper((string)($fundraiserData['currency'] ?? 'USD')),
                'status' => (string)($fundraiserData['status'] ?? 'pending'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            if (!$ok) {
                $con->rollBack();
                return ['success' => false, 'message' => 'Failed to create fundraiser'];
            }

            $fundraiserId = (int)$con->lastInsertId();

            if (!empty($documents)) {
                $docStmt = $con->prepare(
                    "INSERT INTO fundraiser_documents
                    (fundraiser_id, doc_type, file_path, original_name, mime_type, size_bytes, created_at)
                    VALUES
                    (:fundraiser_id, :doc_type, :file_path, :original_name, :mime_type, :size_bytes, :created_at)"
                );

                foreach ($documents as $doc) {
                    $docOk = $docStmt->execute([
                        'fundraiser_id' => $fundraiserId,
                        'doc_type' => (string)$doc['doc_type'],
                        'file_path' => (string)$doc['file_path'],
                        'original_name' => (string)$doc['original_name'],
                        'mime_type' => (string)($doc['mime_type'] ?? ''),
                        'size_bytes' => (int)($doc['size_bytes'] ?? 0),
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);

                    if (!$docOk) {
                        $con->rollBack();
                        return ['success' => false, 'message' => 'Failed to save fundraiser document'];
                    }
                }
            }

            $con->commit();
            return ['success' => true, 'fundraiser_id' => $fundraiserId];
        } catch (Throwable $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getByCreator($userId)
    {
        $query = "SELECT f.*,
                    COALESCE(stats.raised_amount, 0) AS raised_amount,
                    COALESCE(stats.donor_count, 0) AS donor_count
                  FROM fundraisers f
                  LEFT JOIN (
                    SELECT fundraiser_id,
                           SUM(amount) AS raised_amount,
                           COUNT(*) AS donor_count
                    FROM fundraiser_donations
                    WHERE status = 'captured'
                    GROUP BY fundraiser_id
                  ) stats ON stats.fundraiser_id = f.fundraiser_id
                  WHERE f.creator_user_id = :creator_user_id
                  ORDER BY f.created_at DESC";

        $rows = $this->query($query, ['creator_user_id' => (int)$userId]);
        return is_array($rows) ? $rows : [];
    }

    public function getPendingForFacultyAdmin($facultyId = null)
    {
        $params = [];
        $facultyFilter = '';

        if ($facultyId !== null && (int)$facultyId > 0) {
            $facultyFilter = " AND s.faculty_id = :faculty_id";
            $params['faculty_id'] = (int)$facultyId;
        }

        $query = "SELECT f.*,
                    u.name AS creator_name,
                    u.email AS creator_email,
                    s.faculty_id AS creator_faculty_id,
                    fac.faculty_name AS creator_faculty_name,
                    COALESCE(doc.total_docs, 0) AS document_count
                  FROM fundraisers f
                  INNER JOIN users u ON u.user_id = f.creator_user_id
                  LEFT JOIN students s ON s.user_id = f.creator_user_id
                  LEFT JOIN faculties fac ON fac.faculty_id = s.faculty_id
                  LEFT JOIN (
                    SELECT fundraiser_id, COUNT(*) AS total_docs
                    FROM fundraiser_documents
                    GROUP BY fundraiser_id
                  ) doc ON doc.fundraiser_id = f.fundraiser_id
                  WHERE f.status = 'pending' {$facultyFilter}
                  ORDER BY f.created_at ASC";

        $rows = $this->query($query, $params);
        return is_array($rows) ? $rows : [];
    }

        public function getAllForFacultyAdmin($facultyId = null)
        {
                $params = [];
                $facultyFilter = '';

                if ($facultyId !== null && (int)$facultyId > 0) {
                        $facultyFilter = " AND s.faculty_id = :faculty_id";
                        $params['faculty_id'] = (int)$facultyId;
                }

                $query = "SELECT f.*,
                                        u.name AS creator_name,
                                        u.email AS creator_email,
                                        s.faculty_id AS creator_faculty_id,
                                        fac.faculty_name AS creator_faculty_name,
                                        COALESCE(doc.total_docs, 0) AS document_count
                                    FROM fundraisers f
                                    INNER JOIN users u ON u.user_id = f.creator_user_id
                                    LEFT JOIN students s ON s.user_id = f.creator_user_id
                                    LEFT JOIN faculties fac ON fac.faculty_id = s.faculty_id
                                    LEFT JOIN (
                                        SELECT fundraiser_id, COUNT(*) AS total_docs
                                        FROM fundraiser_documents
                                        GROUP BY fundraiser_id
                                    ) doc ON doc.fundraiser_id = f.fundraiser_id
                                    WHERE 1=1 {$facultyFilter}
                                    ORDER BY
                                        CASE f.status
                                            WHEN 'pending' THEN 1
                                            WHEN 'approved' THEN 2
                                            WHEN 'closed' THEN 3
                                            WHEN 'rejected' THEN 4
                                            ELSE 5
                                        END,
                                        f.updated_at DESC,
                                        f.created_at DESC";

                $rows = $this->query($query, $params);
                return is_array($rows) ? $rows : [];
        }

    public function getByIdWithStats($fundraiserId)
    {
        $query = "SELECT f.*,
                    u.name AS creator_name,
                    u.email AS creator_email,
                    COALESCE(stats.raised_amount, 0) AS raised_amount,
                    COALESCE(stats.donor_count, 0) AS donor_count
                  FROM fundraisers f
                  INNER JOIN users u ON u.user_id = f.creator_user_id
                  LEFT JOIN (
                    SELECT fundraiser_id,
                           SUM(amount) AS raised_amount,
                           COUNT(*) AS donor_count
                    FROM fundraiser_donations
                    WHERE status = 'captured'
                    GROUP BY fundraiser_id
                  ) stats ON stats.fundraiser_id = f.fundraiser_id
                  WHERE f.fundraiser_id = :fundraiser_id
                  LIMIT 1";

        $row = $this->get_row($query, ['fundraiser_id' => (int)$fundraiserId]);
        return $row ?: false;
    }

    public function getDocuments($fundraiserId)
    {
        $query = "SELECT *
                  FROM fundraiser_documents
                  WHERE fundraiser_id = :fundraiser_id
                  ORDER BY created_at ASC";
        $rows = $this->query($query, ['fundraiser_id' => (int)$fundraiserId]);
        return is_array($rows) ? $rows : [];
    }

    public function getDocumentForModeration($documentId, $facultyId = null)
    {
        $params = ['document_id' => (int)$documentId];
        $facultyFilter = '';

        if ($facultyId !== null && (int)$facultyId > 0) {
            $facultyFilter = ' AND s.faculty_id = :faculty_id';
            $params['faculty_id'] = (int)$facultyId;
        }

        $query = "SELECT d.document_id,
                         d.fundraiser_id,
                         d.file_path,
                         d.original_name,
                         d.mime_type,
                         d.size_bytes,
                         d.created_at,
                         f.creator_user_id,
                         f.title AS fundraiser_title
                  FROM fundraiser_documents d
                  INNER JOIN fundraisers f ON f.fundraiser_id = d.fundraiser_id
                  LEFT JOIN students s ON s.user_id = f.creator_user_id
                  WHERE d.document_id = :document_id{$facultyFilter}
                  LIMIT 1";

        return $this->get_row($query, $params);
    }

    public function approve($fundraiserId, $adminUserId, $note = '')
    {
        return $this->query(
            "UPDATE fundraisers
             SET status = 'approved',
                 admin_note = :admin_note,
                 approved_by_user_id = :approved_by_user_id,
                 approved_at = NOW(),
                 rejected_at = NULL,
                 updated_at = NOW()
             WHERE fundraiser_id = :fundraiser_id
               AND status = 'pending'",
            [
                'admin_note' => trim((string)$note),
                'approved_by_user_id' => (int)$adminUserId,
                'fundraiser_id' => (int)$fundraiserId,
            ]
        ) !== false;
    }

    public function reject($fundraiserId, $adminUserId, $note)
    {
        return $this->query(
            "UPDATE fundraisers
             SET status = 'rejected',
                 admin_note = :admin_note,
                 approved_by_user_id = :approved_by_user_id,
                 rejected_at = NOW(),
                 updated_at = NOW()
             WHERE fundraiser_id = :fundraiser_id
               AND status = 'pending'",
            [
                'admin_note' => trim((string)$note),
                'approved_by_user_id' => (int)$adminUserId,
                'fundraiser_id' => (int)$fundraiserId,
            ]
        ) !== false;
    }

    public function getApprovedForFeed($excludeCreatorUserId = null)
    {
        $params = [];
        $excludeFilter = '';
        if ($excludeCreatorUserId !== null && (int)$excludeCreatorUserId > 0) {
            $excludeFilter = " AND f.creator_user_id != :exclude_creator_user_id";
            $params['exclude_creator_user_id'] = (int)$excludeCreatorUserId;
        }

        $query = "SELECT f.*,
                    u.name AS creator_name,
                    COALESCE(stats.raised_amount, 0) AS raised_amount,
                    COALESCE(stats.donor_count, 0) AS donor_count,
                    CASE
                        WHEN f.target_amount > 0 THEN ROUND((COALESCE(stats.raised_amount, 0) / f.target_amount) * 100, 2)
                        ELSE 0
                    END AS funded_percent
                  FROM fundraisers f
                  INNER JOIN users u ON u.user_id = f.creator_user_id
                  LEFT JOIN (
                    SELECT fundraiser_id,
                           SUM(amount) AS raised_amount,
                           COUNT(*) AS donor_count
                    FROM fundraiser_donations
                    WHERE status = 'captured'
                    GROUP BY fundraiser_id
                  ) stats ON stats.fundraiser_id = f.fundraiser_id
                  WHERE f.status = 'approved' {$excludeFilter}
                  ORDER BY f.created_at DESC";

        $rows = $this->query($query, $params);
        return is_array($rows) ? $rows : [];
    }

    public function getRecentStoppedForFeed($limit = 10, $excludeCreatorUserId = null)
    {
        $params = [];
        $excludeFilter = '';
        $limitClause = '';

        if ($limit !== null) {
            $limit = (int)$limit;
            if ($limit > 0) {
                $limitClause = " LIMIT {$limit}";
            }
        }

        if ($excludeCreatorUserId !== null && (int)$excludeCreatorUserId > 0) {
            $excludeFilter = ' AND f.creator_user_id != :exclude_creator_user_id';
            $params['exclude_creator_user_id'] = (int)$excludeCreatorUserId;
        }

        $query = "SELECT f.*,
                    u.name AS creator_name,
                    COALESCE(stats.raised_amount, 0) AS raised_amount,
                    COALESCE(stats.donor_count, 0) AS donor_count,
                    CASE
                        WHEN f.target_amount > 0 THEN ROUND((COALESCE(stats.raised_amount, 0) / f.target_amount) * 100, 2)
                        ELSE 0
                    END AS funded_percent
                  FROM fundraisers f
                  INNER JOIN users u ON u.user_id = f.creator_user_id
                  LEFT JOIN (
                    SELECT fundraiser_id,
                           SUM(amount) AS raised_amount,
                           COUNT(*) AS donor_count
                    FROM fundraiser_donations
                    WHERE status = 'captured'
                    GROUP BY fundraiser_id
                  ) stats ON stats.fundraiser_id = f.fundraiser_id
                  WHERE f.status IN ('closed', 'stopped'){$excludeFilter}
                  ORDER BY f.updated_at DESC{$limitClause}";

        $rows = $this->query($query, $params);
        return is_array($rows) ? $rows : [];
    }

    public function getAdminStats($facultyId = null)
    {
        $params = [];
        $facultyJoin = '';
        $facultyWhere = '';

        if ($facultyId !== null && (int)$facultyId > 0) {
            $facultyJoin = " LEFT JOIN students s ON s.user_id = f.creator_user_id ";
            $facultyWhere = " WHERE s.faculty_id = :faculty_id ";
            $params['faculty_id'] = (int)$facultyId;
        }

        $query = "SELECT
                    COUNT(*) AS total_fundraisers,
                    SUM(CASE WHEN f.status = 'pending' THEN 1 ELSE 0 END) AS pending_fundraisers,
                                        SUM(CASE WHEN f.status = 'approved' THEN 1 ELSE 0 END) AS approved_fundraisers,
                                        SUM(CASE WHEN f.status IN ('closed', 'stopped') THEN 1 ELSE 0 END) AS closed_fundraisers,
                                        SUM(CASE WHEN f.status = 'rejected' THEN 1 ELSE 0 END) AS rejected_fundraisers
                  FROM fundraisers f
                  {$facultyJoin}
                  {$facultyWhere}";

        $row = $this->get_row($query, $params);
        if (!$row) {
            return (object)[
                'total_fundraisers' => 0,
                'pending_fundraisers' => 0,
                'approved_fundraisers' => 0,
                'closed_fundraisers' => 0,
                'rejected_fundraisers' => 0,
            ];
        }
        return $row;
    }

    public function closeApprovedByAdmin($fundraiserId, $adminUserId, $facultyId = null)
    {
        $params = [
            'fundraiser_id' => (int)$fundraiserId,
            'admin_user_id' => (int)$adminUserId,
        ];

        $facultyJoin = '';
        $facultyFilter = '';
        if ($facultyId !== null && (int)$facultyId > 0) {
            $facultyJoin = ' LEFT JOIN students s ON s.user_id = f.creator_user_id ';
            $facultyFilter = ' AND s.faculty_id = :faculty_id ';
            $params['faculty_id'] = (int)$facultyId;
        }

        $query = "UPDATE fundraisers f
                  {$facultyJoin}
                  SET f.status = 'closed',
                      f.admin_note = CONCAT(COALESCE(NULLIF(f.admin_note, ''), ''),
                        CASE WHEN COALESCE(NULLIF(f.admin_note, ''), '') <> '' THEN ' | ' ELSE '' END,
                        'Closed by admin'),
                      f.approved_by_user_id = :admin_user_id,
                      f.updated_at = NOW()
                  WHERE f.fundraiser_id = :fundraiser_id
                    AND f.status = 'approved'
                    {$facultyFilter}";

        return $this->query($query, $params) !== false;
    }

    public function stopFundraiser($fundraiserId, $userId)
    {
        // Only allow the creator to stop their own approved/active fundraiser
        return $this->query(
            "UPDATE fundraisers
                         SET status = 'closed', updated_at = NOW()
             WHERE fundraiser_id = :fundraiser_id
               AND creator_user_id = :user_id
               AND status = 'approved'",
            [
                'fundraiser_id' => (int)$fundraiserId,
                'user_id' => (int)$userId,
            ]
        ) !== false;
    }

    public function updatePendingByCreator($fundraiserId, $userId, array $fundraiserData)
    {
        $normalizedTargetAmount = $this->normalizeMoneyValue($fundraiserData['target_amount'] ?? '');
        if ($normalizedTargetAmount === null || (int)str_replace('.', '', $normalizedTargetAmount) < 100) {
            return false;
        }

        try {
            $con = $this->connect();
            $stmt = $con->prepare(
                "UPDATE fundraisers
                 SET title = :title,
                     reasoning = :reasoning,
                     description = :description,
                     target_amount = :target_amount,
                     currency = :currency,
                     updated_at = NOW()
                 WHERE fundraiser_id = :fundraiser_id
                   AND creator_user_id = :creator_user_id
                   AND status = 'pending'"
            );

            $stmt->execute([
                'title' => (string)$fundraiserData['title'],
                'reasoning' => (string)$fundraiserData['description'],
                'description' => (string)$fundraiserData['description'],
                'target_amount' => $normalizedTargetAmount,
                'currency' => strtoupper((string)$fundraiserData['currency']),
                'fundraiser_id' => (int)$fundraiserId,
                'creator_user_id' => (int)$userId,
            ]);

            return $stmt->rowCount() > 0;
        } catch (Throwable $e) {
            return false;
        }
    }

    public function deletePendingByCreator($fundraiserId, $userId)
    {
        $con = $this->connect();
        $con->beginTransaction();

        try {
            $ownedPending = $con->prepare(
                "SELECT fundraiser_id
                 FROM fundraisers
                 WHERE fundraiser_id = :fundraiser_id
                   AND creator_user_id = :creator_user_id
                   AND status = 'pending'
                 LIMIT 1"
            );
            $ownedPending->execute([
                'fundraiser_id' => (int)$fundraiserId,
                'creator_user_id' => (int)$userId,
            ]);

            if (!$ownedPending->fetch(PDO::FETCH_ASSOC)) {
                $con->rollBack();
                return false;
            }

            $deleteDocs = $con->prepare("DELETE FROM fundraiser_documents WHERE fundraiser_id = :fundraiser_id");
            $deleteDocs->execute(['fundraiser_id' => (int)$fundraiserId]);

            $deleteFundraiser = $con->prepare(
                "DELETE FROM fundraisers
                 WHERE fundraiser_id = :fundraiser_id
                   AND creator_user_id = :creator_user_id
                   AND status = 'pending'"
            );
            $deleteFundraiser->execute([
                'fundraiser_id' => (int)$fundraiserId,
                'creator_user_id' => (int)$userId,
            ]);

            if ($deleteFundraiser->rowCount() < 1) {
                $con->rollBack();
                return false;
            }

            $con->commit();
            return true;
        } catch (Throwable $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            return false;
        }
    }
}
