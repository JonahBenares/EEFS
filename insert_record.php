<?php
include 'includes/connection.php';
date_default_timezone_set("Asia/Taipei");
session_start();

$userid = $_SESSION['userid'] ?? 0;

if (!$userid) {
    echo "unauthorized";
    exit;
}

function post($key) {
    return $_POST[$key] ?? '';
}

/* =========================
   INPUTS
========================= */
$doc_id        = (int)post('doc_id');
$doc_type      = post('doc_type');
$location      = post('location');
$doc_date      = post('doc_date');
$company       = post('company');
$department    = post('department');
$subject       = post('subject');
$sender_comp   = post('sender_comp');
$sender_person = post('sender_person');
$add_comp      = post('add_comp');
$add_person    = post('add_person');
$copy_type     = post('copy_type');
$confidential  = post('confidential');
$signatory     = post('signatory');
$remarks       = post('remarks');

$now = date('Y-m-d H:i:s');

/* =========================
   TYPE (UNCHANGED)
========================= */
$stmt = $con->prepare("SELECT type_id FROM document_type WHERE type_name = ?");
$stmt->bind_param("s", $doc_type);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    $stmt = $con->prepare("INSERT INTO document_type (type_name) VALUES (?)");
    $stmt->bind_param("s", $doc_type);
    $stmt->execute();
    $typeid = $con->insert_id;
} else {
    $typeid = $res->fetch_assoc()['type_id'];
}

/* =========================
   LOCATION (UNCHANGED)
========================= */
$stmt = $con->prepare("SELECT location_id FROM document_location WHERE location_name = ?");
$stmt->bind_param("s", $location);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    $stmt = $con->prepare("INSERT INTO document_location (location_name) VALUES (?)");
    $stmt->bind_param("s", $location);
    $stmt->execute();
    $locationid = $con->insert_id;
} else {
    $locationid = $res->fetch_assoc()['location_id'];
}

/* =========================
   INSERT / UPDATE MAIN DOC
========================= */
if ($doc_id == 0) {

    /* ✅ PRODUCTION SAFE INSERT */
    $stmt = $con->prepare("
        INSERT INTO document_info (
            logged_date, document_date, company_id, location_id,
            user_id, type_id, department_id, subject,
            sender_company, sender_person, addressee_company, addressee_person,
            copy_type, confidential, signatory, remarks
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "ssiiisssssssssss",
        $now, $doc_date, $company, $locationid,
        $userid, $typeid, $department, $subject,
        $sender_comp, $sender_person, $add_comp, $add_person,
        $copy_type, $confidential, $signatory, $remarks
    );

    $stmt->execute();

    /* 🔥 SAFE ID FETCH (NO DUPLICATES EVER) */
    $docid = $con->insert_id;

} else {

    $docid = $doc_id;

    $stmt = $con->prepare("
        UPDATE document_info SET
            logged_date=?,
            document_date=?,
            company_id=?,
            location_id=?,
            user_id=?,
            type_id=?,
            department_id=?,
            subject=?,
            sender_company=?,
            sender_person=?,
            addressee_company=?,
            addressee_person=?,
            copy_type=?,
            confidential=?,
            signatory=?,
            remarks=?
        WHERE document_id=?
    ");

    $stmt->bind_param(
        "ssiiisssssssssssi",
        $now, $doc_date, $company, $locationid,
        $userid, $typeid, $department,
        $subject, $sender_comp, $sender_person,
        $add_comp, $add_person,
        $copy_type, $confidential,
        $signatory, $remarks,
        $docid
    );

    $stmt->execute();
}

/* =========================
   SHARING (UNCHANGED)
========================= */
$stmt = $con->prepare("DELETE FROM shared_document WHERE document_id = ?");
$stmt->bind_param("i", $docid);
$stmt->execute();

for ($x = 1; $x <= 3; $x++) {
    $share = post("share$x");

    if (!empty($share)) {
        $stmt = $con->prepare("
            INSERT INTO shared_document (document_id, user_id)
            VALUES (?, ?)
        ");
        $stmt->bind_param("ii", $docid, $share);
        $stmt->execute();
    }
}

/* =========================
   FILE UPLOAD (UNCHANGED)
========================= */
if (!empty($_FILES['attach_file']['name'][0])) {

    $files = $_FILES['attach_file'];
    $names = $_POST['attach_name'] ?? [];

    for ($x = 0; $x < count($files['name']); $x++) {

        if (!empty($files['name'][$x])) {

            $tmp  = $files['tmp_name'][$x];
            $orig = $files['name'][$x];
            $aname = $names[$x] ?? '';

            $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));

            if ($ext === 'php') {
                echo "invalid file";
                exit;
            }

            $afile = time() . "_" . $userid . "_" . $x . "." . $ext;

            move_uploaded_file($tmp, "upload/" . $afile);

            $stmt = $con->prepare("
                INSERT INTO document_attach (document_id, attach_file, attach_remarks)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("iss", $docid, $afile, $aname);
            $stmt->execute();
        }
    }
}

/* =========================
   EXISTING FILES (UNCHANGED)
========================= */
if (!empty($_POST['existing_attach_id'])) {

    foreach ($_POST['existing_attach_id'] as $attach_id) {

        $attach_id = (int)$attach_id;
        $keep = $_POST['existing_keep'][$attach_id] ?? 1;
        $name = $_POST['attach_name_existing'][$attach_id] ?? '';

        if ($keep == "1") {

            if (!empty($_FILES['attach_file_existing']['name'][$attach_id])) {

                $tmp = $_FILES['attach_file_existing']['tmp_name'][$attach_id];
                $orig = $_FILES['attach_file_existing']['name'][$attach_id];
                $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));

                $afile = time() . "_" . $attach_id . "." . $ext;

                move_uploaded_file($tmp, "upload/" . $afile);

                $stmt = $con->prepare("
                    UPDATE document_attach
                    SET attach_file=?, attach_remarks=?
                    WHERE attach_id=?
                ");
                $stmt->bind_param("ssi", $afile, $name, $attach_id);
                $stmt->execute();

            } else {

                $stmt = $con->prepare("
                    UPDATE document_attach
                    SET attach_remarks=?
                    WHERE attach_id=?
                ");
                $stmt->bind_param("si", $name, $attach_id);
                $stmt->execute();
            }

        } else {

            $stmt = $con->prepare("DELETE FROM document_attach WHERE attach_id=?");
            $stmt->bind_param("i", $attach_id);
            $stmt->execute();
        }
    }
}

/* =========================
   RESPONSE
========================= */
echo ($doc_id == 0) ? "added|" . $docid : "updated|" . $docid;
exit;
?>