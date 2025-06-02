<?php

namespace App\Models;

class NewsModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function getAllNews()
    {
            $sql= "SELECT * FROM rssfeed ORDER BY published_at DESC";         
            $stmt = oci_parse($this->conn, $sql);
        oci_execute($stmt);
        $news = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $news[] = $row;
        }
        return $news;

    }
    public function addNews($type, $title, $content, $relatedLink)
    {
        $sql = "INSERT INTO rssfeed (TYPE, TITLE, CONTENT, RELATED_LINK, PUBLISHED_AT) VALUES (:type, :title, :content, :relatedLink, SYSDATE)";
        $stmt = oci_parse($this->conn, $sql);
        oci_bind_by_name($stmt, ':type', $type);
        oci_bind_by_name($stmt, ':title', $title);
        oci_bind_by_name($stmt, ':content', $content);
        oci_bind_by_name($stmt, ':relatedLink', $relatedLink);
        return oci_execute($stmt);
    }
    
}