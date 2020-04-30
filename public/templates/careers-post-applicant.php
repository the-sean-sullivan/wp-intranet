<?php

	require_once($_SERVER["DOCUMENT_ROOT"] . '/2016/wp-load.php');

	class UploadException extends Exception {

	    public function __construct($code) {
	        $message = $this->codeToMessage($code);
	        parent::__construct($message, $code);
	    }

	    private function codeToMessage($code) {
	        switch ($code) {
	            case UPLOAD_ERR_INI_SIZE:
	                $message = "The uploaded file exceeds the maximum allowed.";
	                break;
	            case UPLOAD_ERR_FORM_SIZE:
	                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
	                break;
	            case UPLOAD_ERR_PARTIAL:
	                $message = "The uploaded file was only partially uploaded";
	                break;
	            case UPLOAD_ERR_NO_FILE:
	                $message = "No file was uploaded";
	                break;
	            case UPLOAD_ERR_NO_TMP_DIR:
	                $message = "Missing a temporary folder";
	                break;
	            case UPLOAD_ERR_CANT_WRITE:
	                $message = "Failed to write file to disk";
	                break;
	            case UPLOAD_ERR_EXTENSION:
	                $message = "File upload stopped by extension";
	                break;

	            default:
	                $message = "Unknown upload error";
	                break;
	        }

	        $response['status'] = 'curl';
	        $response['msg'] = $message;
	        echo json_encode($response);
	        exit;
	    }
	}

	if( empty( $_FILES ) && empty( $_POST ) && isset( $_SERVER['REQUEST_METHOD'] ) && strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post') : //catch file overload error...

	  	$response['status'] = 'file_size';
	  	$response['msg'] = '<p>The uploaded file exceeds the maximum allowed.</p>';
	  	echo json_encode($response);

	else:

		if ($_FILES['files']['error'] === UPLOAD_ERR_OK) :

			define("UPLOAD_DIR", "/var/www/vhosts/srsdev.com/sean.srsdev.com/resume/");

			$headers = array("Content-Type:multipart/form-data");
			$response = array();
			$myFile = $_FILES['files'];

		    // set file variables
		    $url = "http://srs.srsdev.com/2016";
		    $filename = $_FILES['files']['name'];
		    $filesize = $_FILES['files']['size'];
		    $filetype = $_FILES["files"]["type"];

		    // only allow pdf and word doc
		    $allowedExts = array("pdf", "doc", "docx");
		    // $extension = end(explode(".", $filename));

		    if (($filetype == "application/pdf") || ($filetype == "application/msword") || ($filetype == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") ) :

		    	// remove slashes
		      	$name = preg_replace("/[^A-Z0-9._-]/i", "_", $filename);

		      	// create new file name
		      	$name = 'srs-upload-'.$name;

				// don't overwrite an existing file
			    $i = 0;
			    $parts = pathinfo($name);
			    while (file_exists(UPLOAD_DIR . $name)) :
			        $i++;
			        $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
			    endwhile;

		      	// move file to directory outside web root
		      	$success = move_uploaded_file($myFile["tmp_name"], UPLOAD_DIR . $name);
				if (!$success) :

					// if it fails kill process
					$response['status'] = 'curl';
					$response['msg'] = '<p>Unable to save file.</p>';
					echo json_encode($response);
					exit;

				else :

					// else save file for CURL
					$filedata = '@'.UPLOAD_DIR . $name.';filename='.$name.';type='.$filetype;

				endif;

				// Change permissions on file
				chmod(UPLOAD_DIR . $name, 0644);

				$title = $_POST['name'];
				$resume = '<a href="http://srs.srsdev.com/resume/' . $name . '" class="button target="_blank" target="_blank">View Resume</a>';

		      	// Add the content of the form to $post as an array
				$post = array(
					'post_title'	=> $title,
					'post_content'	=> 'Nothing here.',
					'post_type'		=> 'applicants'
				);
				$postID = wp_insert_post($post);

				// Update Custom Fields - Field names will have to be changed
				update_field('field_56a7b46043324', $_POST['job_title'], $postID); // Applying For
				update_field('field_56e1c372191e0', $_POST['job_number'], $postID); // Job Number
				update_field('field_56a7b47a43325', $_POST['group'], $postID); // Job Department
				update_field('field_56a7b3d74331d', $title, $postID); // Name
				update_field('field_56a7b3e84331f', $_POST['phone_num'], $postID); // Phone
				update_field('field_56a7b40343320', $_POST['email_add'], $postID); // Email
				update_field('field_56a7b43a43323', $_POST['website'], $postID); // Website Portfolio
				update_field('field_56a7b41843321', $_POST['comments'], $postID); // Questions/Comments
				update_field('field_56e1e04d8b71f', $resume, $postID); // Resume Upload

				// Update Custom Category & Tags
				wp_set_object_terms($postID, $_POST['status'], 'applicant-status');
				wp_set_object_terms($postID, $_POST['group'], 'applicant-dept');

		    else :

		    	// send back error
		    	$response['status'] = 'file';
		    	$response['msg'] = 'Please upload a properly formatted file. Accepted types are PDF(.pdf) and Word Doc(.doc,.docx).';
		    	echo json_encode($response);
		    	exit;

		    endif;

		else :

		 	throw new UploadException($_FILES['files']['error']);

		endif;

	endif;

?>
