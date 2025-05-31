<?php
namespace App\Models;

class SocialModel {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Get groups the user is a member of
    public function getUserGroups($userId) {
        $cursor = oci_new_cursor($this->conn);
        $stmt = oci_parse($this->conn, "BEGIN :result := get_user_groups(:user_id); END;");
        oci_bind_by_name($stmt, ":result", $cursor, -1, OCI_B_CURSOR);
        oci_bind_by_name($stmt, ":user_id", $userId);
        oci_execute($stmt);
        oci_execute($cursor);
        
        $groups = [];
        while ($row = oci_fetch_assoc($cursor)) {
            // Get member count for each group
            $countSql = "SELECT COUNT(*) AS member_count FROM ReadingGroupMembers WHERE group_id = :group_id";
            $countStmt = oci_parse($this->conn, $countSql);
            oci_bind_by_name($countStmt, ":group_id", $row['GROUP_ID']);
            oci_execute($countStmt);
            $countRow = oci_fetch_assoc($countStmt);
            
            $row['MEMBER_COUNT'] = $countRow['MEMBER_COUNT'];
            
            // Get creator name
            $creatorSql = "SELECT username FROM users WHERE user_id = :creator_id";
            $creatorStmt = oci_parse($this->conn, $creatorSql);
            oci_bind_by_name($creatorStmt, ":creator_id", $row['CREATOR_ID']);
            oci_execute($creatorStmt);
            $creatorRow = oci_fetch_assoc($creatorStmt);
            
            $row['CREATOR_NAME'] = $creatorRow['USERNAME'];
            
            $groups[] = $row;
        }
        
        oci_free_statement($stmt);
        oci_free_statement($cursor);
        return $groups;
    }
    
    // Create a new reading group
    public function createGroup($groupName, $userId) {
        $sql = "INSERT INTO ReadingGroup (group_name, creator_id) VALUES (:group_name, :creator_id) 
                RETURNING group_id INTO :group_id";
        
        $stmt = oci_parse($this->conn, $sql);
        $groupId = 0;
        oci_bind_by_name($stmt, ":group_name", $groupName);
        oci_bind_by_name($stmt, ":creator_id", $userId);
        oci_bind_by_name($stmt, ":group_id", $groupId, 32);
        
        $result = oci_execute($stmt);
        
        if ($result) {
            // Add creator as a member
            $this->addGroupMember($groupId, $userId);
            return $groupId;
        }
        
        return false;
    }
    
    // Add a user to a group
    public function addGroupMember($groupId, $userId) {
        $sql = "INSERT INTO ReadingGroupMembers (group_id, user_id) VALUES (:group_id, :user_id)";
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ":group_id", $groupId);
        oci_bind_by_name($stmt, ":user_id", $userId);
        return oci_execute($stmt);
    }
    
    // Assign a book to all members of a group
    public function assignGroupBook($groupId, $bookId) {
        $sql = "BEGIN assign_group_book(:group_id, :book_id); END;";
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ":group_id", $groupId);
        oci_bind_by_name($stmt, ":book_id", $bookId);
        return oci_execute($stmt);
    }
    
    // Get count of users in a group reading a specific book
    public function getGroupReadersCount($groupId, $bookId) {
        $sql = "BEGIN :result := GroupReading.get_group_readers_count(:group_id, :book_id); END;";
        $stmt = oci_parse($this->conn, $sql);
        $result = 0;
        oci_bind_by_name($stmt, ":result", $result, 32);
        oci_bind_by_name($stmt, ":group_id", $groupId);
        oci_bind_by_name($stmt, ":book_id", $bookId);
        oci_execute($stmt);
        return $result;
    }
    
    // Search users by email
    public function searchUsersByEmail($email) {
        try {
            $searchTerm = '%' . strtoupper($email) . '%';
            
            // Use simple query with minimal error points
            $sql = "SELECT user_id, username, email FROM users WHERE UPPER(email) LIKE :email";
            
            $stmt = oci_parse($this->conn, $sql);
            oci_bind_by_name($stmt, ":email", $searchTerm);
            oci_execute($stmt);
            
            $users = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $users[] = [
                    'USER_ID' => $row['USER_ID'],
                    'USERNAME' => $row['USERNAME'], 
                    'EMAIL' => $row['EMAIL']
                ];
            }
            
            oci_free_statement($stmt);
            return $users;
        } catch (\Exception $e) {
            error_log('Error in searchUsersByEmail: ' . $e->getMessage());
            return [];  // Return empty array on error instead of throwing
        }
    }
}