<?php namespace App\Controllers;

use System\BaseController;
use App\Helpers\Session;
use App\Helpers\Url;
use App\Models\Award;
use App\Models\AwardClass;
use App\Models\AwardForm;
use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Awards extends BaseController
{
    protected $award;

    public function __construct()
    {
        parent::__construct();

        if (! Session::get('logged_in')) {
            Url::redirect('/admin/login');
        }

        $this->award = new Award();
    }

    public function index()
    {
        $awards = new Award();
        $records = $awards->getAwardsDetails();

        return $this->view->render('awards/index', compact('records'));
    }

    public function add()
    {
        $errors = [];
        $awardTypes = new AwardClass();
        $records = $awardTypes->getAwardClasses();

        if (isset($_POST['submit'])) {
            $awardeeFullName           = (isset($_POST['awardeeFullName']) ? $_POST['awardeeFullName'] : null);
            $awardeeEmail              = (isset($_POST['awardeeEmail']) ? $_POST['awardeeEmail'] : null);
            $awardeePosition           = (isset($_POST['awardeePosition']) ? $_POST['awardeePosition'] : null);
            $awardeeLocation           = (isset($_POST['awardeeLocation']) ? $_POST['awardeeLocation'] : null);
            $awardeeManager            = (isset($_POST['awardeeManager']) ? $_POST['awardeeManager'] : null);
            $awardDateTime             = (isset($_POST['awardDateTime']) ? $_POST['awardDateTime'] : null);
            $awardClassId              = (isset($_POST['awardClassId']) ? $_POST['awardClassId'] : null);
            if (strlen($awardeeFullName) < 1) {
                $errors[] = 'Awardee name is too short';
            }

            if (!filter_var($awardeeEmail, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address';
            }

            if (count($errors) == 0) {

                $data = [
                    'awardeeFullName' => $awardeeFullName,
                    'awardeeEmail' => $awardeeEmail,
                    'awardeePosition' => $awardeePosition,
                    'awardeeLocation' => $awardeeLocation,
                    'awardeeManager' => $awardeeManager,
                    'awardDateTime' => $awardDateTime,
                    'awardCreatorId' => Session::get('user_id'),
                    'awardClassId' => $awardClassId
                ];

                $this->award->insert($data);

                Session::set('success', 'Award created.  Click "Preview/Send" to review and send the award you created.');

                Url::redirect('/awards');

            }

        }

        $title = 'Create New Award';
        $this->view->render('awards/add', compact('records','errors', 'title'));
    }


    public function edit($id=NULL)
    {
        if (! is_numeric($id)) {
            Url::redirect('/awards');
        }

        $awardForm = new AwardForm();
        $awardForm = $awardForm->get_award_form($id);
        $award = $awardForm['award'];
        $awardTypes = $awardForm['award_types'];

        if ($award == null) {
            Url::redirect('/awards');
        }

        $errors = [];

        if (isset($_POST['submit'])) {
            $awardeeFullName           = (isset($_POST['awardeeFullName']) ? $_POST['awardeeFullName'] : null);
            $awardeeEmail              = (isset($_POST['awardeeEmail']) ? $_POST['awardeeEmail'] : null);
            $awardeePosition           = (isset($_POST['awardeePosition']) ? $_POST['awardeePosition'] : null);
            $awardeeLocation           = (isset($_POST['awardeeLocation']) ? $_POST['awardeeLocation'] : null);
            $awardeeManager            = (isset($_POST['awardeeManager']) ? $_POST['awardeeManager'] : null);
            $awardDateTime             = (isset($_POST['awardDateTime']) ? $_POST['awardDateTime'] : null);
            $awardCreatorId            = (isset($_POST['awardCreatorId']) ? $_POST['awardCreatorId'] : null);
            $awardClassId              = (isset($_POST['awardClassId']) ? $_POST['awardClassId'] : null);
            if (strlen($awardeeFullName) < 1) {
                $errors[] = 'Awardee name is too short';
            }

            if (!filter_var($awardeeEmail, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address';
            }

            if (count($errors) == 0) {

                $data = [
                    'awardeeFullName' => $awardeeFullName,
                    'awardeeEmail' => $awardeeEmail,
                    'awardeePosition' => $awardeePosition,
                    'awardeeLocation' => $awardeeLocation,
                    'awardeeManager' => $awardeeManager,
                    'awardDateTime' => $awardDateTime,
                    'awardClassId' => $awardClassId
                ];

                $where = ['awardId' => $id];

                $this->award->update($data, $where);

                $this->createTex($id);

                Session::set('success', 'Award updated');

                Url::redirect('/awards');

            }

        }

        $title = 'Edit Award';
        $this->view->render('awards/edit', compact('award', 'awardTypes', 'errors', 'title'));
    }

    private function createTex($awardId)
    {
        if (! Session::get('logged_in')) {
            Url::redirect('/admin/login');
        }

        $data = new Award();
        $data = $data->getAwardValues($awardId);

        $fileName = $data->awardeeFullName . date("_Y_m_d_H_i_s");

        $fileName = preg_replace("/[^a-z0-9\_\-]/i", '', $fileName);

        $file = fopen(APPDIR . "Resources/$fileName.tex", "w") or die("Error creating or opening file.");

        fwrite($file, '\documentclass[16pt,a4paper]{scrartcl}' . PHP_EOL);
        fwrite($file, '\usepackage[landscape,left=2cm,right=2cm,top=2cm,bottom=2cm]{geometry}' . PHP_EOL);
        fwrite($file, '\usepackage{setspace} % for spacing between lines' . PHP_EOL);
        fwrite($file, '\usepackage{graphicx} % for including images' . PHP_EOL);
        fwrite($file, '\usepackage{eso-pic}  % for including background image' . PHP_EOL);
        fwrite($file, '\usepackage{color}' . PHP_EOL);
        fwrite($file, '\definecolor{blue}{rgb}{0,0.08,0.45}' . PHP_EOL);
        fwrite($file, '\definecolor{red}{rgb}{0.5,0,0}' . PHP_EOL);
        fwrite($file, '\newcommand\BackgroundPic{' . PHP_EOL);
        fwrite($file, '\put(0,0){' . PHP_EOL);
        fwrite($file, '\parbox[b][\paperheight]{\paperwidth}{%' . PHP_EOL);
        fwrite($file, '\vfill' . PHP_EOL);
        fwrite($file, '\centering' . PHP_EOL);
        fwrite($file, '\includegraphics[width=\paperwidth,height=\paperheight,' . PHP_EOL);
        fwrite($file, 'keepaspectratio]{images/background1.jpg}              % here include background image' . PHP_EOL);
        fwrite($file, '\vfill' . PHP_EOL);
        fwrite($file, '}}}' . PHP_EOL);

        fwrite($file, '\def\signature#1#2{\parbox[b]{1in}{\smash{#1}\vskip12pt}' . PHP_EOL);
        fwrite($file, '\hfill \parbox[t]{2.8in}{\shortstack{\vrule width 2.8in height 0.4pt\\\\\small#2}}}' . PHP_EOL);
        fwrite($file, '\def\sigskip{\vskip0.4in plus 0.1in}' . PHP_EOL);
        fwrite($file, '\def\beginskip{\vskip0.5875in plus 0.1in}' . PHP_EOL);

        fwrite($file, '\begin{document}' . PHP_EOL);
        fwrite($file, '\AddToShipoutPicture{\BackgroundPic}     % here background image is used' . PHP_EOL);

        fwrite($file, '\noindent' . PHP_EOL);
        fwrite($file, '\begin{minipage}[l]{1.5in}' . PHP_EOL);
        fwrite($file, '\includegraphics[width=.92\linewidth]{images/osu_logo.png}  % image appears in the upper left' . PHP_EOL);
        fwrite($file, '\end{minipage}' . PHP_EOL);
        fwrite($file, '\hfill' . PHP_EOL);

        fwrite($file, '\begin{minipage}[c]{6.5in}' . PHP_EOL);
        fwrite($file, '{\centering' . PHP_EOL);
        fwrite($file, '{\onehalfspacing' . PHP_EOL);
        fwrite($file, '{\LARGE\bfseries\color{blue}Oregon State University EECS}\\\\' . PHP_EOL);
        fwrite($file, '{\bfseries\color{blue}Corvallis, OR}\\\\' . PHP_EOL);
        fwrite($file, '}' . PHP_EOL);
        fwrite($file, '}' . PHP_EOL);
        fwrite($file, '\end{minipage}' . PHP_EOL);

        fwrite($file, '\hfill' . PHP_EOL);
        fwrite($file, '\begin{minipage}[r]{1.0in}' . PHP_EOL);
        fwrite($file, '\includegraphics[width=1.2\linewidth]{images/beaver_logo.png}  % image that appears in the upper right' . PHP_EOL);
        fwrite($file, '\end{minipage}' . PHP_EOL);
        fwrite($file, '\hfill' . PHP_EOL);

        fwrite($file, '\noindent' . PHP_EOL);
        fwrite($file, '\begin{minipage}[l]{1.5in}' . PHP_EOL);
        fwrite($file, '\end{minipage}' . PHP_EOL);
        fwrite($file, '\hfill' . PHP_EOL);

        fwrite($file, '\begin{minipage}[c]{10.25in}' . PHP_EOL);
        fwrite($file, '{\centering' . PHP_EOL);
        fwrite($file, '  {\doublespacing' . PHP_EOL);
        fwrite($file, '    {\LARGE\bfseries\color{blue}' . $data->awardName . '}\\\\' . PHP_EOL);
        fwrite($file, '  }' . PHP_EOL);
        fwrite($file, '}' . PHP_EOL);
        fwrite($file, '\end{minipage}' . PHP_EOL);
        fwrite($file, ' \hfill' . PHP_EOL);
        fwrite($file, PHP_EOL);

        fwrite($file, '\begin{minipage}[r]{1.0in}' . PHP_EOL);
        fwrite($file, '\end{minipage}' . PHP_EOL);
        fwrite($file, '\hfill' . PHP_EOL);
        fwrite($file, PHP_EOL);
        fwrite($file, PHP_EOL);
        fwrite($file, PHP_EOL);
        
        fwrite($file, '\vspace{2cm}' . PHP_EOL);
        fwrite($file, '\doublespacing' . PHP_EOL);
        fwrite($file, '\noindent{{\bfseries This is to certify that ' . $data->awardeeFullName . ' has been recognized as ' . PHP_EOL);
        
        fwrite($file, $data->awardName . ' by ' . $data->firstName . ' ' . $data->lastName . ' on ');
        fwrite($file, date("l", strtotime($data->awardDateTime)));
        fwrite($file, ", the ");
        fwrite($file, date("jS \of F", strtotime($data->awardDateTime)));
        fwrite($file, ", ");
        fwrite($file, date("Y", strtotime($data->awardDateTime)));
        fwrite($file, "." . PHP_EOL);
        fwrite($file, '}' . PHP_EOL);
        fwrite($file, '}' . PHP_EOL);

        fwrite($file, '\vspace{2cm}' . PHP_EOL);
        fwrite($file, '\begin{flushright}' . PHP_EOL);
        fwrite($file, '    {\includegraphics[scale=1]{' . $data->signature . '}}\\\\' . PHP_EOL);
        fwrite($file, '\end{flushright}' . PHP_EOL);

        fwrite($file, '\end{document}' . PHP_EOL);

        fclose($file);

        chdir(APPDIR . "Resources");
        exec("pdflatex " . $fileName);

        $fileName = $fileName . ".pdf";

        $data = [
            'awardFilePath' => $fileName,
        ];
        
        $where = ['awardId' => $awardId];
        
        $this->award->update($data, $where);
    }

    public function view($id=NULL)
    {
        if (! is_numeric($id)) {
            Url::redirect('/awards');
        }

        $data = new Award();
        $data = $data->getAwardValues($id);
        
        if ($data->awardFilePath=="") {
            $this->createTex($id);
        }

        $data = new Award();
        $data = $data->getAwardValues($id);

        if ($data == null) {
            Url::redirect('/awards');
        }

        $errors = [];
        $title = "Email Preview";

        $this->view->render('awards/view', compact('data', 'errors', 'title'));
    }

    public function send($awardId)
    {
        if (! Session::get('logged_in')) {
            Url::redirect('/admin/login');
        }

        $data = new Award();
        $data = $data->getAwardValues($awardId);

        $mail = new PHPMailer(true);

        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host         = 'smtp.mail.com';
        $mail->SMTPAuth     = true;
        // $mail->Username     = 'csfang@mail.com';
        $mail->Username     = 'fangrecognition@mail.com';
        $mail->Password     = 'OSU123acj';
        $mail->SMTPSecure   = 'tls';
        $mail->Port         = 587;
           
        // $mail->setFrom('csfang@mail.com'); 
        $mail->setFrom('fangrecognition@mail.com'); 
        // $mail->setFrom($data->email); 
        $mail->addAddress($data->awardeeEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Congratulations on your recognition!';

        $mail->Body    = '<p>Congratulations!</p>
        <p>' . $data->firstName . ' ' . $data->lastName . ' has selected you for the award "' .$data->awardName . '".</p>
        <p>This award was granted on ' .date("l", strtotime($data->awardDateTime)) . ', the ' . 
        date("jS \of F", strtotime($data->awardDateTime)) . ', '. date("Y", strtotime($data->awardDateTime)) . '.</p>
        <p>Please see the attached PDF for details.</p>';

        $mail->AltBody = 'Congratulations!  ' . $data->firstName . ' ' . $data->lastName . ' has selected you for the award "' . $data->awardName . '".
        This award was granted on ' . date("l", strtotime($data->awardDateTime)) . ', the ' . 
        date("jS \of F", strtotime($data->awardDateTime)) . ', '. date("Y", strtotime($data->awardDateTime)) . '.</p>
        
        Please see the attached PDF for details.';

        $fileName = $data->awardFilePath;  // NOTE: This is actually just the filename; TO DO: refactor the name of this field
        $filePath = APPDIR.'Resources\\';
        $mail->AddAttachment($filePath . $fileName);

        $mail->send();

        Session::set('success', "Email sent to ".htmlentities($data->awardeeEmail));

        Url::redirect('/awards');

    }

    public function delete($awardId)
    {
        if (! is_numeric($awardId)) {
            Url::redirect('/awards');
        }

        $award = $this->award->get_award($awardId);

        if ($award == null) {
            Url::redirect('/404');
        }

        $where = ['awardId' => $award->awardId];

        $this->award->delete($where);

        Session::set('success', 'Award deleted');

        Url::redirect('/awards');
    }

    public function sendfile($fileName)
    {
        $filePath = APPDIR.'Resources\\';
        $fp = fopen($filePath . $fileName, 'rb');

        header("Content-Disposition:attachment;filename=" . $fileName);
        header("Content-Length: " . filesize($filePath . $fileName));
        fpassthru($fp);
    }
}