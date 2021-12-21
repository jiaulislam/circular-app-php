<?php
include_once('header_b.php');

$MAXIMUM_FILESIZE = 50 * 1024 * 1024;
$BASE_DIR = 'assets/circulars/';
$ACCEPTED_FILE_TYPES = "/^\.(jpg|jpeg|doc|docx|pdf){1}$/i";

// Sanitize File Name
    function sanitize_file_name($file_name, $user_code, $circular_no) {
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $current_date = date("Ymd");
        return $current_date . '_' . $user_code . '_' . $circular_no . '.' . $file_ext;
    }


    $post_data = $_POST;

    $user_id = $_SESSION['user'];

    $isMoved = false;

    $circular_no = $post_data['circular_no'];

    $_date = date_format(date_create($post_data['published_date']), "d-M-Y");


    $is_file = is_uploaded_file($_FILES['file']['tmp_name']);


    // check if this is a real file
    if ($is_file){
        $file_name = sanitize_file_name($_FILES['file']['name'], $user_id, $circular_no);

        $_query = "INSERT INTO IPL.PLIL_CIRCULAR (PUBLISHED_DATE, CIRCULAR_NO, TITLE, FILE_NAME, DEPTCODE)
                    VALUES (:published_date, :circular_no, :title, :file_name, :department)";

        $stid = oci_parse($conn, $_query);

        $bind = array(":published_date" => $_date, ":circular_no" => $post_data["circular_no"], ":title" => $post_data["title"], ":file_name" => $file_name, ":department" => $post_data["department"]);

        foreach($bind as $key => $val){
        oci_bind_by_name($stid, $key, $bind[$key]);
        }

        $isInserted = oci_execute($stid, OCI_NO_AUTO_COMMIT);

        if ($isInserted && $_FILES['file']['size'] <= $MAXIMUM_FILESIZE && preg_match($ACCEPTED_FILE_TYPES, strrchr($file_name, '.'))){
            $isMoved = move_uploaded_file($_FILES['file']['tmp_name'], $BASE_DIR . $file_name);
        }
       
    }
    
    if ($isMoved){

        if ($isInserted) {
            oci_commit($conn);
            echo "
                <div class='result-success'>
                    Sucessfully Uploaded
                </div>
                <div class='container mx-auto'>
                    <a class='btn btn-success d-grid mw-60 align'
                        href='/pil_bus_pd/search_circular.php'>Home<span><svg xmlns='http://www.w3.org/2000/svg'
                                width='16' height='16' fill='currentColor' class='bi bi-house-fill ms-1'
                                viewBox='0 0 16 16'>
                                <path fill-rule='evenodd'
                                    d='m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z' />
                                <path fill-rule='evenodd'
                                    d='M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z' />
                            </svg>
                        </span>
                    </a>
                </div>
            ";
        }
        else {
            echo "
            <div class='result-error'>
                Database Error ! Please Try Again.
            </div>
            <div class='container d-grid justify-content-center'>
                <button class='btn btn-primary d-inline-grid justify-content-center align-items-center' onclick='history.back()'>
                    GO BACK
                    <span>
                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-arrow-left-circle mx-1 d-inline-grid justify-content-center align-items-center' viewBox='0 0 16 16'>
                        <path fill-rule='evenodd' d='M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z'/>
                    </svg>
                </span>
                </button>
            </div>
            ";
        }
    }
    else {
        echo "
            <div class='result-error'>
                Unsupported File type. Please upload `pdf or img` files only.
            </div>
            <div class='container d-grid justify-content-center'>
                <button class='btn btn-primary d-inline-grid justify-content-center align-items-center' onclick='history.back()'>
                    GO BACK
                    <span>
                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-arrow-left-circle mx-1 d-inline-grid justify-content-center align-items-center' viewBox='0 0 16 16'>
                        <path fill-rule='evenodd' d='M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z'/>
                    </svg>
                </span>
                </button>
            </div>
            ";
    }

?>

<script>

</script>