<?php

function require_role(string $expected_role)
{
    if (empty($_SESSION['role']) || $_SESSION['role'] !== $expected_role) {
        http_response_code(403);
        throw new Exception("403 forbidden - You do not have acccess to this site");
    }
}
?>