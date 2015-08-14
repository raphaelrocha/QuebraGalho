<?php 
header ('Content-type: text/html; charset=UTF-8');

/*
* @ localhost = 0;
* @ ica = 1;
* @ hostinger = 2;
*/
$serverLocation = 0;

$host;
$db;
$user;
$passwd;

if($serverLocation == 0){
	$host = 'localhost';
	$db = 'quebragalho';
	$user = 'quebragalho_user';
	$passwd = '@#RJQUEBRAgalho@#UFAM@#2015';
} else if($serverLocation == 1){
	$host = 'localhost';
	$db = 'quebragalho';
	$user = 'quebragalho_user';
	$passwd = '@#RJQUEBRAgalho@#UFAM@#2015';
} else if($serverLocation == 2){
	$host = 'mysql.hostinger.com.br';
	$db = 'u992178256_qg';
	$user = 'u992178256_qg';
	$passwd = 'shellscript';
}

$conn = new mysqli($host,$user,$passwd,$db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!$conn->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $conn->error);
} 
/*else {
    printf("Current character set: %s\n", $conn->character_set_name());
} */

function getArrayPro($model){
	$arrayProfessional = array("id"=>$model["ID"],
							   "id_user"=>$model["ID_USER"],
								"name"=>$model["NAME"],
								"email"=>$model["EMAIL"],
								"birth"=>$model["BIRTH"],
								"sex"=>$model["SEX"],
								"picture_profile"=>$model["PICTURE_PROFILE"],
								"socialnet"=>$model["SOCIALNET"],
								"is_pro"=>$model["IS_PRO"],
								"banner"=>$model["BANNER"],
								"city"=>$model["CITY"],
								"state"=>$model["STATE"],
								"addr"=>$model["ADDR"],
								"district"=>$model["DISTRICT"],
								"phone1"=>$model["PHONE1"],
								"phone2"=>$model["PHONE2"],
								"location"=>$model["LOCATION"],
								"description"=>$model["DESCRIPTION"]);
	return $arrayProfessional;
}

function getArrayUser($model){
	$arrayUser = array("id"=>$model["ID"],
								"name"=>$model["NAME"],
								"email"=>$model["EMAIL"],
								"birth"=>$model["BIRTH"],
								"sex"=>$model["SEX"],
								"picture_profile"=>$model["PICTURE_PROFILE"],
								"socialnet"=>$model["SOCIALNET"],
								"is_pro"=>$model["IS_PRO"],
								"id_pro"=>$model["ID_PRO"],
								);
	return $arrayUser;
}

function getArrayCategory($model){
	$arrayCategory = array("id"=>$model["ID"],
						   "description"=>$model["DESCRIPTION"],
						   "ic"=>$model["IC"]);
	return $arrayCategory;
}

function getArraySubcategory($model){
	$arraySubcategory = array('id'=>$model["ID"],
							  'id_category'=>$model["ID_CATEGORY"],
						      'description'=>$model["DESCRIPTION"],
						      'ic'=>$model["IC"]);
	return $arraySubcategory;
}

function getArraySubcWithCat($model){
	$arraySubcategory = array('id'=>'found',
							  'id_cat'=>$model["ID_CAT"],
							  'name_cat'=>$model["NAME_CAT"],
						      'ic_cat'=>$model["IC_CAT"],
						      'id_subcat'=>$model["ID_SUBCAT"],
							  'name_subcat'=>$model["NAME_SUBCAT"],
						      'ic_subcat'=>$model["IC_SUBCAT"],
						      );
	return $arraySubcategory;
}

function getArrayAlbum($model){
	$arrayAlbum = array('id'=>$model["ID"],
					    'id_professional'=>$model["ID_PROFESSIONAL"],
					    'description'=>$model["DESCRIPTION"],
					    'picture'=>$model["PICTURE"]);
	return $arrayAlbum;
}

function getArrayCommentary($model){
	$arrayAlbum = array('id'=>'found',
						'name'=>$model["NAME_USER"],
						'picture_profile'=>$model["PICTURE_PROFILE_USER"],
						'id_commentary'=>$model["ID_COMMENTARY"],
						'id_user'=>$model["ID_USER_COMMENTARY"],
						'id_professional'=>$model["ID_PROFESSIONAL_COMMENTARY"],
						'rating'=>$model["RATING_COMMENTARY"],
						'phrase_commentary'=>$model["PHRASE_COMMENTARY"],
						'commentary_date_formated'=>$model["DATE_FORMATED_COMMENTARY"],
						'id_response'=>$model["ID_RESPONSE"],
						'email_response'=>$model["EMAIL_RESPONSE"],
					    'response_phrase'=>$model["PHRASE_RESPONSE"],
					    'response_date_formated'=>$model["DATE_FORMATED_RESPONSE"]);
      
	return $arrayAlbum;
}

function selectProById($conn,$id){
	$sql = "SELECT PROFESSIONAL.ID,
				   PROFESSIONAL.ID_USER,
				   USER.NAME,
				   USER.EMAIL,
				   USER.BIRTH,
				   USER.SEX,
				   USER.PICTURE_PROFILE,
				   USER.SOCIALNET,
				   USER.IS_PRO,
				   PROFESSIONAL.BANNER,
				   PROFESSIONAL.CITY,
				   PROFESSIONAL.STATE,
				   PROFESSIONAL.ADDR,
				   PROFESSIONAL.DISTRICT,
				   PROFESSIONAL.PHONE1,
				   PROFESSIONAL.PHONE2,
				   PROFESSIONAL.LOCATION,
				   PROFESSIONAL.DESCRIPTION
				   FROM USER JOIN PROFESSIONAL
				   ON (USER.ID = PROFESSIONAL.ID_USER)
				   WHERE PROFESSIONAL.ID='".$id."'";
	return $conn->query($sql);
}

function selectFavorites($conn,$idUserLogged){
	$sql = "SELECT PROFESSIONAL.ID,
				   PROFESSIONAL.ID_USER,
				   USER.NAME,
				   USER.EMAIL,
				   USER.BIRTH,
				   USER.SEX,
				   USER.PICTURE_PROFILE,
				   USER.SOCIALNET,
				   USER.IS_PRO,
				   PROFESSIONAL.BANNER,
				   PROFESSIONAL.CITY,
				   PROFESSIONAL.STATE,
				   PROFESSIONAL.ADDR,
				   PROFESSIONAL.DISTRICT,
				   PROFESSIONAL.PHONE1,
				   PROFESSIONAL.PHONE2,
				   PROFESSIONAL.LOCATION,
				   PROFESSIONAL.DESCRIPTION
                   FROM USER JOIN PROFESSIONAL
				   ON (USER.ID = PROFESSIONAL.ID_USER) JOIN FAVORITES
                   ON (PROFESSIONAL.ID=FAVORITES.ID_PRO)
                   WHERE FAVORITES.ID_USER='".$idUserLogged."'";
	return $conn->query($sql);
}

function selectRating($conn,$idProfessional){
	$sql = "SELECT ROUND(AVG(RATING),1) AS AVG, count(*) AS TOTAL 
			FROM COMMENTARY 
			WHERE ID_PROFESSIONAL=".$idProfessional;
	return $conn->query($sql);
}

function selectSubcategoryByPro($conn,$idProfessional){
	$sql = "SELECT DESCRIPTION 
			FROM PRO_SUBCAT JOIN SUBCATEGORY 
			ON(SUBCATEGORY.ID=PRO_SUBCAT.ID_SUBCAT) 
			WHERE ID_PRO=$idProfessional;";
	return $conn->query($sql);
}

function saveFileInFolder($fileString,$filename){
	
}

function validaEmail($email) {
	$er = "/^(([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}){0,1}$/";
    if (preg_match($er, $email)){
	return true;
    } else {
	return false;
    }
}

function saveFile($fileString,$filename){
	$binary = base64_decode($fileString);
    header('Content-Type: bitmap; charset=utf-8');

    $file = fopen('images/__w-200-400-600-800-1000__/' . $filename, 'wb');
    // Create File
    fwrite($file, $binary);
    fclose($file);

    $file = fopen('images/w200/' . $filename, 'wb');
    // Create File
    fwrite($file, $binary);
    fclose($file);

    $file = fopen('images/w400/' . $filename, 'wb');
    // Create File
    fwrite($file, $binary);
    fclose($file);

    $file = fopen('images/w600/' . $filename, 'wb');
    // Create File
    fwrite($file, $binary);
    fclose($file);

    $file = fopen('images/w800/' . $filename, 'wb');
    // Create File
    fwrite($file, $binary);
    fclose($file);

    $file = fopen('images/w1000/' . $filename, 'wb');
    // Create File
    fwrite($file, $binary);
    fclose($file);

    return true;
}

if(isset($_POST['method'])){
	if(strcmp('create-pro', $_POST['method']) == 0){ // SEND
		//$data = utf8_encode($_POST['data']);
		//$data2 = utf8_encode($_POST['data2']);
		//$data = json_decode($data);
		//$data2 = json_decode($data2);
		$data = json_decode($_POST['data']);
		$data2 = json_decode($_POST['data2']);

		$username = $data->email;
		$password = $data->passwd;
		$fileString = $data->picture_profile;
		$ext = $data->extension;
		
		$now = date("D M j G:i:s T Y");
		$filename = md5($data->email.$now);
		$filename = $filename.".".$ext ;


		if($fileString!="vazio"){
			$returnFileSave = saveFile($fileString,$filename);
		}else{
			$filename="n_perfil.jpg";
		}
		
		if(validaEmail($username)){
		    $sql = "INSERT INTO USER VALUES
				(NULL,
				'$data->name',
				'$data->email',
				'$data->birth',
				'$data->sex',
				'$filename',
				'$data->socialnet',
				'$data->passwd',
				'$data->is_pro',
				NULL)";

			if ($conn->query($sql) === TRUE) {
				$userID = $conn->insert_id;
				$fileStringBanner = $data2->banner;
				$extBanner = $data2->extension_banner;
				
				$now = date("D M j G:i:s T Y");
				$filenameBanner = md5($data->email.$now.banner);
				$filenameBanner = $filenameBanner.".".$extBanner ;

				if($fileString!="vazio"){
					$returnFileSaveBanner = saveFile($fileStringBanner,$filenameBanner);
				}else{
					$filenameBanner="default_banner.jpg";
				}

				$sql = "";
				$sql = "INSERT INTO PROFESSIONAL VALUES
					(NULL,
					'$userID',
					'$filenameBanner',
					'$data2->city',
					'$data2->state',
					'$data2->addr',
					'$data2->district',
					'$data2->phone1',
					'$data2->phone2',
					'$data2->location',
					'$data2->description',
					NULL)";

				if ($conn->query($sql) === TRUE) {
					$sql = "SELECT SELECT USER.ID,
										   USER.NAME,
										   USER.BIRTH,
										   USER.SEX,
										   USER.PICTURE_PROFILE,
										   USER.SOCIALNET,
										   USER.IS_PRO,
										   USER.DATE_TIME_USER,
										   (PROFESSIONAL.ID)AS ID_PRO,
										   PROFESSIONAL.BANNER,
										   PROFESSIONAL.CITY,
										   PROFESSIONAL.STATE,
										   PROFESSIONAL.ADDR,
										   PROFESSIONAL.DISTRICT,
										   PROFESSIONAL.PHONE1,
										   PROFESSIONAL.PHONE2,
										   PROFESSIONAL.LOCATION,
										   PROFESSIONAL.DATE_TIME_PROF FROM USER LEFT JOIN PROFESSIONAL ON (USER.ID=PROFESSIONAL.ID_USER)  WHERE EMAIL='".$username."' AND PASSWD='".$password."'" ;
		
					$result = $conn->query($sql);
					if($result->num_rows > 0){
						$arrayLogin = array();
						foreach($result as $model){
							$arrayLogin = getArrayUser($model);
						}
						echo json_encode($arrayLogin);
					}else{
						echo json_encode(array('id'=>'-1'));//login invalido	
					}
				} else {
				    echo json_encode(array('id'=>'-2'));//erro de cadastro
				}
			  
			} else {
			    echo json_encode(array('id'=>'-2'));//erro de cadastro
			}
		}else{
			echo json_encode(array('id'=>'-3'));//email invalido
		}
	}


	/*
	@ TIPO DE RETORNO = JSONARRAY
	SALVA UMA FOTO PARA O ALBUM DO PROFISSIONAL
	*/
	else if(strcmp('send-photo', $_POST['method']) == 0){
		//$data = utf8_encode($_POST['data']);
		//$data = json_decode($data);
		$data = json_decode($_POST['data']);
		$fileString = $data->photo;
		$fileStringRsz = $data->photo_rsz;
		$legend = $data->legend;
		$idPro = $data->id_pro;
		$emailPro = $data->email_pro;
		$ext = $data->extension;
		/*$fileString = utf8_encode($_POST['data']);
		$fileStringRsz = utf8_encode($_POST['imgRsz']);
		$legend = utf8_encode($_POST['legend']);
		$idPro = utf8_encode($_POST['idPro']);
		$emailPro = utf8_encode($_POST['emailPro']);*/

		$now = date("D M j G:i:s T Y");
		$filename = md5($emailPro.$now);
		$filename = $filename.".".$ext;

		$filenamersz = "rsz_".$filename;

		if($fileString!="vazio"){
			$returnFileSave = saveFile($fileString,$filename);
			/*$binary = base64_decode($fileString);
		    header('Content-Type: bitmap; charset=utf-8');

		    $file = fopen('images/__w-200-400-600-800-1000__/' . $filename, 'wb');
		    // Create File
		    fwrite($file, $binary);
		    fclose($file);

		    $file = fopen('images/w200/' . $filename, 'wb');
		    // Create File
		    fwrite($file, $binary);
		    fclose($file);

		    $file = fopen('images/w400/' . $filename, 'wb');
		    // Create File
		    fwrite($file, $binary);
		    fclose($file);

		    $file = fopen('images/w600/' . $filename, 'wb');
		    // Create File
		    fwrite($file, $binary);
		    fclose($file);

		    $file = fopen('images/w800/' . $filename, 'wb');
		    // Create File
		    fwrite($file, $binary);
		    fclose($file);

		    $file = fopen('images/w1000/' . $filename, 'wb');
		    // Create File
		    fwrite($file, $binary);
		    fclose($file);*/


		    $returnFileSaveIcon = saveFile($fileStringRsz,$filenamersz);
		    /*$binaryRsz = base64_decode($fileStringRsz);
		    header('Content-Type: bitmap; charset=utf-8');

		    $file1 = fopen('images/__w-200-400-600-800-1000__/' . $filenamersz, 'wb');
		    // Create File
		    fwrite($file1, $binaryRsz);
		    fclose($file1);

		    $file1 = fopen('images/w200/' . $filenamersz, 'wb');
		    // Create File
		    fwrite($file1, $binaryRsz);
		    fclose($file1);

		    $file1 = fopen('images/w400/' . $filenamersz, 'wb');
		    // Create File
		    fwrite($file1, $binaryRsz);
		    fclose($file1);

		    $file1 = fopen('images/w600/' . $filenamersz, 'wb');
		    // Create File
		    fwrite($file1, $binaryRsz);
		    fclose($file1);

		    $file1 = fopen('images/w800/' . $filenamersz, 'wb');
		    // Create File
		    fwrite($file1, $binaryRsz);
		    fclose($file1);

		    $file1 = fopen('images/w1000/' . $filenamersz, 'wb');
		    // Create File
		    fwrite($file1, $binaryRsz);
		    fclose($file1);*/
		}else{
			$filename="n_perfil.jpg";
		}

		$sql = "INSERT INTO ALBUM VALUES (NULL,$idPro,'$legend','$filename',NULL)";
		$arrayAll = array();

		if ($conn->query($sql) === TRUE) {
			array_push($arrayAll, array('id'=>'true'));
			echo json_encode($arrayAll);
		}else{
			array_push($arrayAll, array('id'=>'false'));
			echo json_encode($arrayAll);	
		}

	}

	/*
	@TIPO DE RETORNO = JSONOBJECT
	CADASTRA O USUÁRIO E RETORNA CONFIRMAÇÃO.
	*/
	else if (strcmp('create-user', $_POST['method']) == 0){ // SEND
		//$data = utf8_encode($_POST['data']);
		$data = json_decode($_POST['data']);
		//$data = json_decode($data);
		$username = $data->email;
		$password = $data->passwd;
		$fileString = $data->picture_profile;
		$ext = $data->extension;
		
		$now = date("D M j G:i:s T Y");
		$filename = md5($data->email.$now);
		$filename = $filename.".".$ext ;


		if($fileString!="vazio"){
			$returnFileSave = saveFile($fileString,$filename);
			/*$binary = base64_decode($fileString);
		    header('Content-Type: bitmap; charset=utf-8');

		    $file = fopen('images/__w-200-400-600-800-1000__/' . $filename, 'wb');
		    // Create File
		    fwrite($file, $binary);
		    fclose($file);

		    $file = fopen('images/w200/' . $filename, 'wb');
		    // Create File
		    fwrite($file, $binary);
		    fclose($file);

		    $file = fopen('images/w400/' . $filename, 'wb');
		    // Create File
		    fwrite($file, $binary);
		    fclose($file);

		    $file = fopen('images/w600/' . $filename, 'wb');
		    // Create File
		    fwrite($file, $binary);
		    fclose($file);

		    $file = fopen('images/w800/' . $filename, 'wb');
		    // Create File
		    fwrite($file, $binary);
		    fclose($file);

		    $file = fopen('images/w1000/' . $filename, 'wb');
		    // Create File
		    fwrite($file, $binary);
		    fclose($file);*/
		}else{
			$filename="n_perfil.jpg";
		}
		
		//$ret = validaEmail($username);
		//echo $ret;

		//if (strpos($username,'@') !== false) {
		if(validaEmail($username)){
		    $sql = "INSERT INTO USER VALUES
				(NULL,
				'$data->name',
				'$data->email',
				'$data->birth',
				'$data->sex',
				'$filename',
				'$data->socialnet',
				'$data->passwd',
				'$data->is_pro',
				NULL)";

			if ($conn->query($sql) === TRUE) {
			    $sql = "SELECT SELECT USER.ID,
					   USER.NAME,
					   USER.BIRTH,
					   USER.SEX,
					   USER.PICTURE_PROFILE,
					   USER.SOCIALNET,
					   USER.IS_PRO,
					   USER.DATE_TIME_USER,
					   (PROFESSIONAL.ID)AS ID_PRO,
					   PROFESSIONAL.BANNER,
					   PROFESSIONAL.CITY,
					   PROFESSIONAL.STATE,
					   PROFESSIONAL.ADDR,
					   PROFESSIONAL.DISTRICT,
					   PROFESSIONAL.PHONE1,
					   PROFESSIONAL.PHONE2,
					   PROFESSIONAL.LOCATION,
					   PROFESSIONAL.DATE_TIME_PROF FROM USER LEFT JOIN PROFESSIONAL ON (USER.ID=PROFESSIONAL.ID_USER) WHERE EMAIL='".$username."' AND PASSWD='".$password."'" ;
		
				$result = $conn->query($sql);
				if($result->num_rows > 0){
					$arrayLogin = array();
					foreach($result as $model){
						$arrayLogin = getArrayUser($model);
					}
					echo json_encode($arrayLogin);
				}else{
					echo json_encode(array('id'=>'-1'));//login invalido	
				}
			} else {
			    echo json_encode(array('id'=>'-2'));//erro de cadastro
			}
		}else{
			echo json_encode(array('id'=>'-3'));//email invalido
		}
	}

	/*
	@TIPO DE RETORNO = JSONARRAY
	RETORNAR TODOS OS PROFISSIONAIS DA BASE DE DADOS.
	*/
	else if(strcmp('get-all-pro', $_POST['method']) == 0){
		$sql = "SELECT PROFESSIONAL.ID,
				   PROFESSIONAL.ID_USER,
				   USER.NAME,
				   USER.EMAIL,
				   USER.BIRTH,
				   USER.SEX,
				   USER.PICTURE_PROFILE,
				   USER.SOCIALNET,
				   USER.IS_PRO,
				   PROFESSIONAL.BANNER,
				   PROFESSIONAL.CITY,
				   PROFESSIONAL.STATE,
				   PROFESSIONAL.ADDR,
				   PROFESSIONAL.DISTRICT,
				   PROFESSIONAL.PHONE1,
				   PROFESSIONAL.PHONE2,
				   PROFESSIONAL.LOCATION,
				   PROFESSIONAL.DESCRIPTION
				   FROM USER JOIN PROFESSIONAL
				   ON (USER.ID = PROFESSIONAL.ID_USER)";
		$result = $conn->query($sql);
		$arrayAll = array();
		if($result->num_rows > 0){
			foreach($result as $model){
				$arrayProfessional = getArrayPro($model);
				//$sql = "SELECT ROUND(AVG(RATING),1) AS AVG, count(*) AS TOTAL FROM COMMENTARY WHERE ID_PROFESSIONAL=".$model["ID"];
				//$resultAvg = $conn->query($sql);
				$resultAvg = selectRating($conn,$model["ID"]);
				if($resultAvg->num_rows > 0){
					foreach($resultAvg as $avg){
						if($avg["AVG"]==null){
							$arrayProfessional['rating_average']='0.0';
							$arrayProfessional['total_rating']='0';
							$arrayProfessional['subcategory']="null";
						}else{
							$arrayProfessional['rating_average']=$avg["AVG"];
							$arrayProfessional['total_rating']=$avg["TOTAL"];
							$arrayProfessional['subcategory']="null";
						}
					}
				}
				array_push($arrayAll, $arrayProfessional);
			}
			echo json_encode($arrayAll);
		}else{
			array_push($arrayAll, array('id'=>'not_found'));
			echo json_encode($arrayAll);	
			//echo json_encode(array('id'=>'not_found'));	
		}
	}

	/*
	@TIPOD DE RETORNO = JSONOBJECT
	RETORNA APENAS UM PROFISSIONAL PELO ID.
	*/
	else if(strcmp('get-pro-by-id', $_POST['method']) == 0){
		$id = $_POST['data'];

		$result = selectProById($conn,$id);

		if($result->num_rows > 0){
			foreach($result as $model){
				$arrayProfessional = getArrayPro($model);
				//$sql = "SELECT ROUND(AVG(RATING),1) AS AVG, count(*) AS TOTAL FROM COMMENTARY WHERE ID_PROFESSIONAL=".$id;
				//$resultAvg = $conn->query($sql);
				$resultAvg = selectRating($conn,$id);
				if($resultAvg->num_rows > 0){
					foreach($resultAvg as $avg){
						if($avg["AVG"]==null){
							$arrayProfessional['rating_average']='0.0';
							$arrayProfessional['total_rating']='0';
						}else{
							$arrayProfessional['rating_average']=$avg["AVG"];
							$arrayProfessional['total_rating']=$avg["TOTAL"];
						}
					}
				}
				/*$sql = "SELECT DESCRIPTION 
						FROM PRO_SUBCAT JOIN SUBCATEGORY 
						ON(SUBCATEGORY.ID=PRO_SUBCAT.ID_SUBCAT) 
						WHERE ID_PRO=$id;";
				$resultCat = $conn->query($sql);*/
				$resultCat = selectSubcategoryByPro($conn,$id);
				$subcats="";
				if($resultCat->num_rows > 0){
					foreach($resultCat as $cat){
						if($subcats==""){
							$subcats = $cat["DESCRIPTION"];
						}else{
							$subcats = $subcats.";".$cat["DESCRIPTION"];
						}
					}
					$arrayProfessional['subcategory']=$subcats;
				}else{
					$arrayProfessional['subcategory']="null";
				}
			}
			echo json_encode($arrayProfessional);
		}else{
			echo json_encode(array('id'=>'not_found'));	
		}
	}

	/*
	@TIPO DE RETORNO = JSONARRAY
	RETORNA TODAS AS CATEGORIAS EXISTENTES NA BASE.
	*/
	else if(strcmp('get-category', $_POST['method']) == 0){

		$sql = "SELECT * FROM CATEGORY";

		$result = $conn->query($sql);
		$arrayAll = array();
		if($result->num_rows > 0){
			foreach($result as $model){
				$arrayCategory = getArrayCategory($model);
				array_push($arrayAll, $arrayCategory);
			}
			echo json_encode($arrayAll);
		}else{
			array_push($arrayAll, array('id'=>'not_found'));
			echo json_encode($arrayAll);	
			//echo json_encode(array('id'=>'not_found'));	
		}
	}

	/*
	@TIPO DE RETORNO = JSONARRAY
	RETORNA TODAS AS SUBCATEGORIAS DE UMA CATEGORIA REFERENTE AO ID RECEBIDO.
	*/
	else if(strcmp('get-subcategory', $_POST['method']) == 0){
		$id_category = $_POST['data'];
		$sql = "SELECT * FROM SUBCATEGORY WHERE ID_CATEGORY ='".$id_category."'";

		$result = $conn->query($sql);
		$arrayAll = array();

		if($result->num_rows > 0){
			
			foreach($result as $model){
				$arraySubcategory = getArraySubcategory($model);
				array_push($arrayAll, $arraySubcategory);
			}
			echo json_encode($arrayAll);
		}else{
			array_push($arrayAll, array('id'=>'not_found'));
			echo json_encode($arrayAll);	
			//echo json_encode(array('id'=>'not_found'));	
		}
	}

	/*
	@TIPO DE RETORNO = JSONARRAY
	RETORNA TODO OS PROFISSIONAIS ASSOCIADOS A UMA SUBCATEGORIA
	*/
	else if(strcmp('get-subcat-list-pro', $_POST['method']) == 0){
		$id_subcat = $_POST['data'];
		
		$sql = "SELECT PROFESSIONAL.ID,
				   PROFESSIONAL.ID_USER,
				   USER.NAME,
				   USER.EMAIL,
				   USER.BIRTH,
				   USER.SEX,
				   USER.PICTURE_PROFILE,
				   USER.SOCIALNET,
				   USER.IS_PRO,
				   PROFESSIONAL.BANNER,
				   PROFESSIONAL.CITY,
				   PROFESSIONAL.STATE,
				   PROFESSIONAL.ADDR,
				   PROFESSIONAL.DISTRICT,
				   PROFESSIONAL.PHONE1,
				   PROFESSIONAL.PHONE2,
				   PROFESSIONAL.LOCATION,
				   PROFESSIONAL.DESCRIPTION
				   FROM SUBCATEGORY JOIN PRO_SUBCAT
				   ON (SUBCATEGORY.ID = PRO_SUBCAT.ID_SUBCAT) JOIN PROFESSIONAL
				   ON (PROFESSIONAL.ID = PRO_SUBCAT.ID_PRO) JOIN USER
				   ON (USER.ID = PROFESSIONAL.ID_USER)
				   WHERE SUBCATEGORY.ID = '".$id_subcat."'";
		$result = $conn->query($sql);
		$arrayAll = array();
		if($result->num_rows > 0){
			
			foreach($result as $model){
				$arrayProfessional = getArrayPro($model);
				$arrayProfessional['rating_average']='3.3';
				array_push($arrayAll, $arrayProfessional);
			}
			echo json_encode($arrayAll);
		}else{
			array_push($arrayAll, array('id'=>'not_found'));
			echo json_encode($arrayAll);	
			//echo json_encode(array('id'=>'not_found'));	
		}
	}

	/*
	@ TIPO DE RETORNO = JSONARRAY
	RETORNA O ALBUM DE UM DETERMINADO PROFISSIONAL
	*/
	else if(strcmp('get-album', $_POST['method']) == 0){

		$id_professional = $_POST['data'];
		$sql = "SELECT * FROM ALBUM WHERE ID_PROFESSIONAL = '".$id_professional."' ORDER BY DATE_TIME_ALBUM DESC";
		
		$result = $conn->query($sql);
		$arrayAll = array();
		if($result->num_rows > 0){
			foreach($result as $model){
				$arrayAlbum = getArrayAlbum($model);
				array_push($arrayAll, $arrayAlbum);
			}
			echo json_encode($arrayAll);
		}else{
			array_push($arrayAll, array('id'=>'not_found'));
			echo json_encode($arrayAll);	
			//echo json_encode(array('id'=>'not_found'));	
		}
	}

	/*
	@TIPO DE RETORNO = JSONOBJECT
	RETORNA APENAS UMA FOTO DO ALBUM.
	*/
	else if(strcmp('get-photo', $_POST['method']) == 0){
		$id = $_POST['data'];

		$sql = "SELECT * FROM ALBUM WHERE ID = '".$id."'";
		
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			foreach($result as $model){
				$arrayAlbum = getArrayAlbum($model);
			}
			echo json_encode($arrayAlbum);
		}else{
			echo json_encode(array('id'=>'not_found'));	
		}
	}

	/*
	@TIPO DE RETORNO = JSONOBJECT
	RETORNA UM ´PROFISSIONAL ALEATÓRIO
	*/
	else if(strcmp('get-aleatory', $_POST['method']) == 0){
		$sql = "SELECT MAX(ID) AS LAST_ID FROM PROFESSIONAL" ;
		$lastId;
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			foreach ($result as $value) {
				$lastId = $value["LAST_ID"];
			}
		}
		$id = rand(1,$lastId);

		$result = selectProById($conn,$id);

		if($result->num_rows > 0){
			foreach($result as $model){
				$arrayProfessional = getArrayPro($model);
				//$sql = "SELECT ROUND(AVG(RATING),1) AS AVG, count(*) AS TOTAL FROM COMMENTARY WHERE ID_PROFESSIONAL=".$id;
				//$resultAvg = $conn->query($sql);
				$resultAvg = selectRating($conn,$id);
				if($resultAvg->num_rows > 0){
					foreach($resultAvg as $avg){
						if($avg["AVG"]==null){
							$arrayProfessional['rating_average']='0.0';
							$arrayProfessional['total_rating']='0';
						}else{
							$arrayProfessional['rating_average']=$avg["AVG"];
							$arrayProfessional['total_rating']=$avg["TOTAL"];
						}
					}
				}
				$resultCat = selectSubcategoryByPro($conn,$id);
				$subcats="";
				if($resultCat->num_rows > 0){
					foreach($resultCat as $cat){
						if($subcats==""){
							$subcats = $cat["DESCRIPTION"];
						}else{
							$subcats = $subcats.";".$cat["DESCRIPTION"];
						}
					}
					$arrayProfessional['subcategory']=$subcats;
				}else{
					$arrayProfessional['subcategory']="null";
				}
			}
			echo json_encode($arrayProfessional);
		}else{
			echo json_encode(array('id'=>'not_found'));	
		}
	}
	/*
	@TIPO DE RETORNO = JSONOJECT
	VALIDA LOGIN
	*/
	else if(strcmp('login', $_POST['method']) == 0){
		$username = $_POST['username'];
		$password = $_POST['password'];
		$sql = "SELECT USER.ID,
					   USER.NAME,
					   USER.BIRTH,
					   USER.SEX,
					   USER.PICTURE_PROFILE,
					   USER.SOCIALNET,
					   USER.IS_PRO,
					   USER.DATE_TIME_USER,
					   (PROFESSIONAL.ID)AS ID_PRO,
					   PROFESSIONAL.BANNER,
					   PROFESSIONAL.CITY,
					   PROFESSIONAL.STATE,
					   PROFESSIONAL.ADDR,
					   PROFESSIONAL.DISTRICT,
					   PROFESSIONAL.PHONE1,
					   PROFESSIONAL.PHONE2,
					   PROFESSIONAL.LOCATION,
					   PROFESSIONAL.DATE_TIME_PROF
					   FROM USER LEFT JOIN PROFESSIONAL ON (USER.ID=PROFESSIONAL.ID_USER) WHERE EMAIL='".$username."' AND PASSWD='".$password."'" ;
		$lastId;
		$result = $conn->query($sql);
		
		if($result->num_rows > 0){
			$arrayLogin = array();
			foreach($result as $model){
				$arrayLogin = getArrayUser($model);
			}
			
			if($arrayLogin["is_pro"]=="1"){
				$id_pro = $arrayLogin["id_pro"];
				$sql = "SELECT COUNT( * ) AS TOTAL
					FROM  PRO_SUBCAT 
					WHERE ID_PRO=$id_pro";
				$resultCount = $conn->query($sql);
				if($resultCount->num_rows > 0){
					foreach($resultCount as $model){
						$arrayLogin['count_cat'] = $model["TOTAL"];
					}
				}else{
					$arrayLogin['count_cat'] = "-1";
				}
			}else{
				$arrayLogin['count_cat'] = "-2";
			}
			echo json_encode($arrayLogin);
		}else{
			echo json_encode(array('id'=>'-1'));	
		}
	}

	/*
	@TIPO DE RETORNO = JSONOBJECT
	RETORNA UM USUÁRIO
	*/
	else if(strcmp('get-user-by-id', $_POST['method']) == 0){
		$username = $_POST['data'];
		$sql = "SELECT SELECT USER.ID,
					   USER.NAME,
					   USER.BIRTH,
					   USER.SEX,
					   USER.PICTURE_PROFILE,
					   USER.SOCIALNET,
					   USER.IS_PRO,
					   USER.DATE_TIME_USER,
					   (PROFESSIONAL.ID)AS ID_PRO,
					   PROFESSIONAL.BANNER,
					   PROFESSIONAL.CITY,
					   PROFESSIONAL.STATE,
					   PROFESSIONAL.ADDR,
					   PROFESSIONAL.DISTRICT,
					   PROFESSIONAL.PHONE1,
					   PROFESSIONAL.PHONE2,
					   PROFESSIONAL.LOCATION,
					   PROFESSIONAL.DATE_TIME_PROF FROM USER LEFT JOIN PROFESSIONAL ON (USER.ID=PROFESSIONAL.ID_USER) WHERE EMAIL='".$username."'" ;
		$result = $conn->query($sql);

		if($result->num_rows > 0){
			foreach($result as $model){
				$arrayUser = getArrayUser($model);
			}
			echo json_encode($arrayUser);
		}else{
			echo json_encode(array('id'=>'not_found'));	
		}
	}

	/*
	@ TIPO DE RETORNO = JSONARRAY
	RETORNA OS FAVORITOS DO USUARIO LOGADO
	*/
	else if(strcmp('get-favorites', $_POST['method']) == 0){

		$idUserLogged = $_POST['data'];	
		$result = selectFavorites($conn,$idUserLogged);
		$arrayAll = array();
		if($result->num_rows > 0){
			foreach($result as $model){
				$arrayFavorites = getArrayPro($model);
				//$sql = "SELECT ROUND(AVG(RATING),1) AS AVG FROM COMMENTARY WHERE ID_PROFESSIONAL=".$model['ID'];
				//$resultAvg = $conn->query($sql);
				$resultAvg = selectRating($conn,$model['ID']);
				if($resultAvg->num_rows > 0){
					foreach($resultAvg as $avg){
						if($avg["AVG"]==null){
							$arrayFavorites['rating_average']='0.0';
							$arrayFavorites['total_rating']='0';
						}else{
							$arrayFavorites['rating_average']=$avg["AVG"];
							$arrayFavorites['total_rating']=$avg["TOTAL"];
						}
					}
				}
				$resultCat = selectSubcategoryByPro($conn,$id);
				$subcats="";
				if($resultCat->num_rows > 0){
					foreach($resultCat as $cat){
						if($subcats==""){
							$subcats = $cat["DESCRIPTION"];
						}else{
							$subcats = $subcats.";".$cat["DESCRIPTION"];
						}
					}
					$arrayFavorites['subcategory']=$subcats;
				}else{
					$arrayFavorites['subcategory']="null";
				}

				array_push($arrayAll, $arrayFavorites);
			}
			echo json_encode($arrayAll);
		}else{
			array_push($arrayAll, array('id'=>'not_found'));
			echo json_encode($arrayAll);	
			//echo json_encode(array('id'=>'not_found'));	
		}
	}

	/*
	@ TIPO DE RETORNO = JSONOBJECT
	MARCA UM FAVORITO
	*/
	else if(strcmp('set-favorite', $_POST['method']) == 0){
		list($idUserLogged,$idProfessional) = explode(";",$_POST['data']);
		
		$sql = "INSERT INTO FAVORITES VALUES (".$idUserLogged.",".$idProfessional.",NULL)";

		if ($conn->query($sql) === TRUE) {
		    echo json_encode(array('id'=>'0x0x0'));
		} else {
		    echo json_encode(array('id'=>'0x0x1'));
		}

	}

	/*
	@ TIPO DE RETORNO = JSONOBJECT
	DESMARCA UM FAVORITO
	*/
	else if(strcmp('unset-favorite', $_POST['method']) == 0){
		list($idUserLogged,$idProfessional) = explode(";",$_POST['data']);
		
		$sql = "DELETE FROM FAVORITES WHERE ID_USER=".$idUserLogged." AND ID_PRO=".$idProfessional;

		if ($conn->query($sql) === TRUE) {
		    echo json_encode(array('id'=>'sucess'));
		} else {
		    echo json_encode(array('id'=>'error'));
		}

	}

	/*
	@ TIPO DE RETORNO = JSONOBJECT
	VERIFICA SE É FAVORITO
	*/
	else if(strcmp('is-favorite', $_POST['method']) == 0){
		list($idUserLogged,$idProfessional) = explode(";",$_POST['data']);
		
		$sql = "SELECT count(*) AS COUNT FROM FAVORITES WHERE ID_USER=".$idUserLogged." AND ID_PRO=".$idProfessional;

		$result = $conn->query($sql);

		if($result->num_rows > 0){
			foreach($result as $model){
				if($model['COUNT']=='1'){
					echo json_encode(array('id'=>'true'));
				}
				else{
					echo json_encode(array('id'=>'false'));
				}
			}
		}else{
			echo json_encode(array('id'=>'false'));
		}
	}


	/*
	@ TIPO DE RETORNO = JSONOBJECT
	INSERE UM COMENTÁRIO
	*/
	else if(strcmp('set-commentary', $_POST['method']) == 0){
		list($idUserLogged,$idProfessional,$rating,$phrase) = explode(";",$_POST['data']);
		
		$sql = "INSERT INTO COMMENTARY VALUES (NULL,$idUserLogged,$idProfessional,$rating,'$phrase',NULL)";

		$result = $conn->query($sql);

		if($result==true){
			echo json_encode(array('id'=>'true'));
		}else{
			echo json_encode(array('id'=>'false'));
		}
	}

	/*
	@ TIPO DE RETORNO = JSONOBJECT
	RECUPERA UM COMENTÁRIO
	*/
	else if(strcmp('get-commentary', $_POST['method']) == 0){
		list($idUserLogged,$idProfessional) = explode(";",$_POST['data']);
		
		//$sql = "SELECT *,DATE_FORMAT(`DATE_TIME`,'%d/%m/%Y') AS `DATE_FORMATED` FROM COMMENTARY JOIN USER ON (COMMENTARY.ID_USER = USER.ID) WHERE ID_USER=$idUserLogged AND ID_PROFESSIONAL=$idProfessional";
		$sql = "SELECT 	USER.NAME AS NAME_USER,
						USER.PICTURE_PROFILE AS PICTURE_PROFILE_USER,
						COMMENTARY.ID AS ID_COMMENTARY,
						COMMENTARY.ID_USER AS ID_USER_COMMENTARY,
						COMMENTARY.ID_PROFESSIONAL AS ID_PROFESSIONAL_COMMENTARY,
						COMMENTARY.RATING AS RATING_COMMENTARY,
						COMMENTARY.PHRASE AS PHRASE_COMMENTARY,
						DATE_FORMAT(`DATE_TIME_COMMENTARY`,'%d/%m/%Y') AS `DATE_FORMATED_COMMENTARY`,
						RESPONSE.ID AS ID_RESPONSE,
						RESPONSE.EMAIL AS EMAIL_RESPONSE,
                        RESPONSE.PHRASE AS PHRASE_RESPONSE,
                   		DATE_FORMAT(`DATE_TIME_RESPONSE`,'%d/%m/%Y') AS `DATE_FORMATED_RESPONSE`
                   FROM (COMMENTARY  LEFT JOIN USER ON (COMMENTARY.ID_USER = USER.ID)) LEFT JOIN RESPONSE ON (COMMENTARY.ID=RESPONSE.ID_COMMENTARY) 
                   WHERE ID_USER=$idUserLogged AND ID_PROFESSIONAL=$idProfessional
                   ORDER BY DATE_TIME_COMMENTARY";

		$result = $conn->query($sql);

		if($result->num_rows > 0){
			foreach($result as $model){
				$getArrayCommentary = getArrayCommentary($model);
			}
			echo json_encode($getArrayCommentary);
		}else{
			echo json_encode(array('id'=>'not_found'));
		}
	}

	/*
	@ TIPO DE RETORNO = JSONARRAY
	RECUPERA TODOS OS COMENTÁRIO
	*/
	else if(strcmp('get-all-commentary-by-id', $_POST['method']) == 0){
		$idProfessional = $_POST['data'];
		
		//$sql = "SELECT *,DATE_FORMAT(`DATE_TIME`,'%d/%m/%Y') AS `DATE_FORMATED` FROM COMMENTARY JOIN USER ON (COMMENTARY.ID_USER = USER.ID) WHERE ID_PROFESSIONAL=$idProfessional";
		$sql = "SELECT 	USER.NAME AS NAME_USER,
						USER.PICTURE_PROFILE AS PICTURE_PROFILE_USER,
						COMMENTARY.ID AS ID_COMMENTARY,
						COMMENTARY.ID_USER AS ID_USER_COMMENTARY,
						COMMENTARY.ID_PROFESSIONAL AS ID_PROFESSIONAL_COMMENTARY,
						COMMENTARY.RATING AS RATING_COMMENTARY,
						COMMENTARY.PHRASE AS PHRASE_COMMENTARY,
						DATE_FORMAT(`DATE_TIME_COMMENTARY`,'%d/%m/%Y') AS `DATE_FORMATED_COMMENTARY`,
						RESPONSE.ID AS ID_RESPONSE,
						RESPONSE.EMAIL AS EMAIL_RESPONSE,
                        RESPONSE.PHRASE AS PHRASE_RESPONSE,
                   		DATE_FORMAT(`DATE_TIME_RESPONSE`,'%d/%m/%Y') AS `DATE_FORMATED_RESPONSE`
                   FROM (COMMENTARY  LEFT JOIN USER ON (COMMENTARY.ID_USER = USER.ID)) LEFT JOIN RESPONSE ON (COMMENTARY.ID=RESPONSE.ID_COMMENTARY) 
                   WHERE ID_PROFESSIONAL=$idProfessional 
                   ORDER BY DATE_TIME_COMMENTARY";

		$result = $conn->query($sql);
		$arrayAll = array();
		if($result->num_rows > 0){

			foreach($result as $model){
				$getArrayCommentary = getArrayCommentary($model);
				array_push($arrayAll, $getArrayCommentary);
			}
			echo json_encode($arrayAll);
		}else{
			array_push($arrayAll, array('id'=>'not_found'));
			echo json_encode($arrayAll);
		}
	}

	/*
	@ TIPO DE RETORNO = JSONOBJECT
	ATUALIZA UM COMENTARIO
	*/
	else if(strcmp('update-commentary', $_POST['method']) == 0){
		list($idUserLogged,$idProfessional,$rating,$phrase) = explode(";",$_POST['data']);
		
		$sql = "UPDATE COMMENTARY SET RATING = $rating, PHRASE = '$phrase' WHERE ID_USER=$idUserLogged AND ID_PROFESSIONAL=$idProfessional;";

		$result = $conn->query($sql);

		if($result==true){
			echo json_encode(array('id'=>'true'));
		}else{
			echo json_encode(array('id'=>'false'));
		}
	}

	/*
	@ TIPO DE RETORNO = JSONOBJECT
	REMOVE UM COMENTARIO
	*/
	else if(strcmp('remove-commentary', $_POST['method']) == 0){
		$idCommentary = $_POST['data'];
		
		$sql = "DELETE FROM COMMENTARY WHERE ID=$idCommentary;";

		$result = $conn->query($sql);
		$arrayResponse = array();	
		if($result==true){
			array_push($arrayResponse, array('id'=>'true'));
			echo json_encode($arrayResponse);
		}else{
			array_push($arrayResponse, array('id'=>'false'));
			echo json_encode($arrayResponse);
		}
	}

	/*
	@ TIPO DE RETORNO = JSONARRAY
	SALVA UMA RESPOSTA
	*/
	else if(strcmp('set-response', $_POST['method']) == 0){
		list($idCommentary,$emailUserLogged,$phrase) = explode(";",$_POST['data']);
		
		$sql = "INSERT INTO RESPONSE VALUES (NULL,'$idCommentary','$emailUserLogged','$phrase',NULL)";
		$arrayAll = array();
		if ($conn->query($sql) === TRUE) {
			array_push($arrayAll, array('id'=>'true'));
		    echo json_encode($arrayAll);
		} else {
		    array_push($arrayAll, array('id'=>'false'));
		    echo json_encode($arrayAll);
		}
	}

	/*
	@ TIPO DE RETORNO = JSONARRAY
	ATUALIZA UMA RESPOSTA
	*/
	else if(strcmp('update-response', $_POST['method']) == 0){
		list($idResponse,$phrase) = explode(";",$_POST['data']);
		
		$sql = "UPDATE RESPONSE SET PHRASE = '$phrase' WHERE ID=$idResponse;";

		$result = $conn->query($sql);
		$arrayAll = array();
		if($result==true){
			array_push($arrayAll, array('id'=>'true'));
		    echo json_encode($arrayAll);
		}else{
			array_push($arrayAll, array('id'=>'false'));
		    echo json_encode($arrayAll);
		}
	}

	/*
	@ TIPO DE RETORNO = JSONARRAY
	RETORNAR SUBCATEGORIAS AGRUPADAS POR CATEGORIAS
	*/
	else if(strcmp('get-all-subcat-with-cat', $_POST['method']) == 0){	
		$sql = "SELECT  CATEGORY.ID AS ID_CAT,
						CATEGORY.DESCRIPTION AS NAME_CAT,
				        CATEGORY.IC AS IC_CAT,
						SUBCATEGORY.ID AS ID_SUBCAT,
					    SUBCATEGORY.DESCRIPTION AS NAME_SUBCAT,
					    SUBCATEGORY.IC AS IC_SUBCAT
				FROM CATEGORY JOIN SUBCATEGORY ON( CATEGORY.ID = SUBCATEGORY.ID_CATEGORY)
				ORDER BY CATEGORY.DESCRIPTION, SUBCATEGORY.DESCRIPTION;";

		$result = $conn->query($sql);
		$arrayAll = array();
		if($result->num_rows > 0){
			foreach($result as $model){
				$arraySubCatWithCat = getArraySubcWithCat($model);
				array_push($arrayAll, $arraySubCatWithCat);
			}
			echo json_encode($arrayAll);
		}else{
			array_push($arrayAll, array('id'=>'not_found'));
			echo json_encode($arrayAll);
		}
	}

	/*
	@TIPO DE RETORNO = JSONOBJECT
	EXCLUI UMA FOTO.
	*/
	else if(strcmp('delete-photo', $_POST['method']) == 0){
		$id = $_POST['data'];

		$sql = "DELETE FROM ALBUM WHERE ID=".$id;

		if ($conn->query($sql) === TRUE) {
		    echo json_encode(array('id'=>'true'));
		} else {
		    echo json_encode(array('id'=>'false'));
		}
	}

	/*
	@ TIPO DE RETORNO = JSONARRAY
	MARCA AS SUBCATEGORIAS
	*/
	else if(strcmp('set-category', $_POST['method']) == 0){
		//$data = utf8_encode($_POST['data']);
		$data = json_decode($_POST['data']);
		//$data = json_decode($data);
		$id_pro =$_POST['id_pro'];
		$fail=0;
		$sucess=0;

		foreach ($data as $value) {
			$sql = "INSERT INTO PRO_SUBCAT VALUES($id_pro,$value,NULL)";
			if ($conn->query($sql) === TRUE) {
				$sucess=$sucess+1;
			} else {
			    $fail=fail+1;
			}
		}

		$arrayAll = array();
		if($fail==0 AND $sucess>0){
			array_push($arrayAll, array('id'=>'set-category-ok','sucess'=>$sucess,'failed'=>$fail));
			echo json_encode($arrayAll);
		}
		else if ($fail>0 AND $sucess>0){
			array_push($arrayAll, array('id'=>'set-category-ok','sucess'=>$sucess,'failed'=>$fail));
			echo json_encode($arrayAll);
		}else{
			array_push($arrayAll, array('id'=>'set-category-fail','sucess'=>$sucess,'failed'=>$fail));
			echo json_encode($arrayAll);
		}
	}

	/*
	@ TIPO DE RETORNO = JSONARRAY
	ALTERA AS SUBCATEGORIAS
	*/
	else if(strcmp('update-pro-category', $_POST['method']) == 0){
		//$data = utf8_encode($_POST['data']);
		//$data = json_decode($data);
		$data = json_decode($_POST['data']);
		$id_pro =$_POST['id_pro'];
		$fail=0;
		$sucess=0;

		$sql = "DELETE FROM PRO_SUBCAT WHERE ID_PRO=$id_pro";

		$arrayAll = array();
		if ($conn->query($sql) === TRUE) {
			$sql="";
			foreach ($data as $value) {
				$sql = "INSERT INTO PRO_SUBCAT VALUES($id_pro,$value,NULL)";
				if ($conn->query($sql) === TRUE) {
					$sucess=$sucess+1;
				} else {
				    $fail=fail+1;
				}
			}

			
			if($fail==0 AND $sucess>0){
				array_push($arrayAll, array('id'=>'update-category-ok','sucess'=>$sucess,'failed'=>$fail));
				echo json_encode($arrayAll);
			}
			else if ($fail>0 AND $sucess>0){
				array_push($arrayAll, array('id'=>'update-category-ok','sucess'=>$sucess,'failed'=>$fail));
				echo json_encode($arrayAll);
			}else{
				array_push($arrayAll, array('id'=>'update-category-fail','sucess'=>$sucess,'failed'=>$fail));
				echo json_encode($arrayAll);
			}
		} else {
		    array_push($arrayAll, array('id'=>'update-category-fail','sucess'=>$sucess,'failed'=>$fail));
			echo json_encode($arrayAll);
		}
		
	}

	/*
	@ TIPO DE RETORNO = JSONARRAY
	RETORNAR TODDAS AS SUBCATEGORIAS DO PROFISSIONAL
	*/
	else if(strcmp('get-ref-subcat-by-pro', $_POST['method']) == 0){
		$id_pro = $_POST['id_pro'];
		$sql = "SELECT *
				FROM PRO_SUBCAT
				WHERE ID_PRO=$id_pro";

		$result = $conn->query($sql);
		$arrayAll = array();
		if($result->num_rows > 0){
			foreach($result as $model){
				$arrayRefSubcatByPro = array('id'=>'refs-found',
											 'id_pro'=>$model["ID_PRO"],
											   'id_subcat'=>$model["ID_SUBCAT"],
											   'date_time'=>$model["DATE_TIME_PRO_SUBCAT"]);
				array_push($arrayAll, $arrayRefSubcatByPro);
			}
			echo json_encode($arrayAll);
		}else{
			array_push($arrayAll, array('id'=>'not_found'));
			echo json_encode($arrayAll);
		}
	}

	/*
	@TIPO DE RETORNO = JSONOBJECT
	ATUALIZA A DESCRIÇÃO.
	*/
	else if(strcmp('update-description', $_POST['method']) == 0){
		$id_pro = $_POST['data'];
		$description = $_POST['data2'];

		$sql = "UPDATE PROFESSIONAL SET DESCRIPTION = '$description' WHERE ID = $id_pro";

		if ($conn->query($sql) === TRUE) {
		    echo json_encode(array('id'=>'true'));
		} else {
		    echo json_encode(array('id'=>'false'));
		}
	}
	
	else{
		echo json_encode(array('id'=>'0x1'));
	}

}else{
	/*
	ERRO GERAL
	*/
	echo json_encode(array('id'=>'1x0'));
}
 ?>