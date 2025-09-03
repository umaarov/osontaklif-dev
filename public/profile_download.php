<?php
require_once __DIR__ . '/../config/bootstrap_secure.php';

use App\Models\Education;
use App\Models\Experience;
use App\Models\User;
use App\Models\UserProfile;

$username = $_GET['user'] ?? null;
if (!$username) {
    exit('User not specified.');
}

$user = User::findBy('username', $username);
if (!$user) {
    exit('User not found.');
}

$profile = UserProfile::findBy('user_id', $user->id);
$experiences = Experience::findByUserId($user->id);
$educations = Education::findByUserId($user->id);

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($user->first_name . ' ' . ($profile->surname ?? ''));
$pdf->SetTitle('Profile of ' . $user->first_name);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

$html = '<h1>' . htmlspecialchars($user->first_name . ' ' . ($profile->surname ?? '')) . '</h1>';
$html .= '<h2>' . htmlspecialchars($profile->headline ?? '') . '</h2>';
$html .= '<p>' . htmlspecialchars($profile->location ?? '') . '</p><hr>';
$html .= '<h3>About</h3><p>' . nl2br(htmlspecialchars($profile->bio ?? '')) . '</p>';

if (!empty($experiences)) {
    $html .= '<h3>Work Experience</h3>';
    foreach ($experiences as $exp) {
        $html .= '<b>' . htmlspecialchars($exp->job_title) . '</b> at ' . htmlspecialchars($exp->company_name) . '<br>';
        $html .= '<small>' . date('M Y', strtotime($exp->start_date)) . ' - ' . ($exp->end_date ? date('M Y', strtotime($exp->end_date)) : 'Present') . '</small>';
        $html .= '<p>' . nl2br(htmlspecialchars($exp->description ?? '')) . '</p>';
    }
}
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output($user->username . '_profile.pdf', 'D');