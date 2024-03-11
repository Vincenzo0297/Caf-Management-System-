<?php
class BackButton {
    public static function generateBackButton($userType) {
        switch ($userType) {
            case 'system admin':
                return '<li><a href="../dashboard/dashboard_admin.php">Back</a></li>';
            case 'cafe owner':
                return '<li><a href="../dashboard/dashboard_owner.php">Back</a></li>';
            case 'cafe manager':
                return '<li><a href="../dashboard/dashboard_manager.php">Back</a></li>';
            case 'cafe staff':
                return '<li><a href="../dashboard/dashboard_staff.php">Back</a></li>';
            default:
                return '';
        }
    }
}
?>