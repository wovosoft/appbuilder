<?php


namespace App\Assets;


class Utils
{
    public static array $languages = [
        "php",
        "javascript",
        "c#",
        "ruby",
        "vb",
        "python"
    ];
    public static array $frameworks = [
        "laravel",
        "django",
        "codeigniter",
        "ruby on rails",
        "asp.net"
    ];
    public static array $databases = [
        "MySQL",
        "SQLite",
        "Postgresql",
        "Oracle"
    ];
    public static array $environments = [
        "production",
        "local",
        "development",
        "staged"
    ];
    public static array $issueStatus = [
        "new",
        "pending",
        "ongoing",
        "cancelled",
        "resolved"
    ];
    public static array $adminPermissions = [
        "is_admin"
    ];
    public static array $branchPermissions = [
        "is_branch"
    ];

}
