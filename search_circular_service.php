<?php
    
    function get_circular($conn, $department_code = NULL, $published_date = NULL, $circular_no = NULL){
        if ($department_code || $published_date || $circular_no){
            $query = "SELECT ID, PUBLISHED_DATE, CIRCULAR_NO, TITLE, FILE_NAME, (SELECT b.DEPTNAME FROM IPL.DEPARTMENT b WHERE b.DEPTCODE = a.DEPTCODE ) DEPTNAME FROM IPL.PLIL_CIRCULAR a
                        WHERE (a.DEPTCODE= :DEPTCODE OR :DEPTCODE IS NULL)
                        AND (TO_CHAR(a.PUBLISHED_DATE,'YYYYMMDD')= :PUBLISHED_DATE OR :PUBLISHED_DATE IS NULL)
                        AND (a.CIRCULAR_NO= :CIRCULAR_NO OR :CIRCULAR_NO IS NULL)
                        ORDER BY a.ID DESC";
            $query_stmt = OCIParse($conn, $query);
            oci_bind_by_name($query_stmt, ":CIRCULAR_NO", $circular_no);
            oci_bind_by_name($query_stmt, ":DEPTCODE", $department_code);
            oci_bind_by_name($query_stmt, ":PUBLISHED_DATE", $published_date);
            OCIExecute($query_stmt);
            oci_fetch_all($query_stmt, $result);
            return $result;
        }else{
            $query = "SELECT A.*, B.DEPTNAME FROM IPL.PLIL_CIRCULAR A , IPL.DEPARTMENT B
                         WHERE A.DEPTCODE = B.DEPTCODE ORDER BY A.ID DESC";
            $query_stmt = OCIParse($conn, $query);
            OCIExecute($query_stmt);
            oci_fetch_all($query_stmt, $result);
            return $result;
        }
    }

    function get_department($conn) {
        $query = "SELECT DEPTCODE, DEPTNAME FROM IPL.DEPARTMENT";
        $query_stmt = OCIParse($conn, $query);
        OCIExecute($query_stmt);
        oci_fetch_all($query_stmt, $result);
        return $result;
    }


?>