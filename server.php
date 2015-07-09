<?php 
header ('Content-type: text/html; charset=UTF-8');
$host = 'localhost';
$db = 'quebragalho';
$user = 'quebragalho_user';
$passwd = '@#RJQUEBRAgalho@#UFAM@#2015';

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

function getArrayAlbum($model){
	$arrayAlbum = array('id'=>$model["ID"],
					    'id_professional'=>$model["ID_PROFESSIONAL"],
					    'description'=>$model["DESCRIPTION"],
					    'picture'=>$model["PICTURE"]);
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
				   WHERE PROFESSIONAL.ID_USER='".$id."'";
		return $conn->query($sql);
}

if(isset($_POST['method'])){
	if(strcmp('create-pro', $_POST['method']) == 0){ // SEND
		echo $_POST['data'];
		$model = new Professional;
		$data = utf8_encode($_POST['data']);
		$data = json_decode($data);

		$model->NAME = $data->name;
		$model->SEX = $data->sex;
		$model->BIRTH = $data->birth;
		$model->ADDR = $data->addr;
		$model->FONE1 = $data->fone1;
		$model->FONE2 = $data->fone2;
		$model->SOCIALNET = $data->socialnet;
		$model->EMAIL = $data->email;
		$model->LOCATION = $data->location;
		$model->PASSWD = $data->passwd;
		$model->DESCRIPTION = $data->description;
		
		if(!$model->save()){
			echo"error";
			print_r($model->getErrors());
		}
	}

	/*
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
		if($result->num_rows > 0){
			$arrayAll = array();
			foreach($result as $model){
				$arrayProfessional = getArrayPro($model);
				array_push($arrayAll, $arrayProfessional);
			}
			echo json_encode($arrayAll);
		}else{
			echo json_encode(array('id'=>'not_found'));	
		}
	}

	/*
	RETORNA APENAS UM PROFISSIONAL PELO ID.
	*/
	else if(strcmp('get-pro-by-id', $_POST['method']) == 0){
		$id = $_POST['data'];

		$result = selectProById($conn,$id);

		if($result->num_rows > 0){
			foreach($result as $model){
				$arrayProfessional = getArrayPro($model);
			}
			echo json_encode($arrayProfessional);
		}else{
			echo json_encode(array('id'=>'not_found'));	
		}
	}

	/*
	RETORNA TODAS AS CATEGORIAS EXISTENTES NA BASE.
	*/
	else if(strcmp('get-category', $_POST['method']) == 0){

		$sql = "SELECT * FROM CATEGORY";

		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$arrayAll = array();
			foreach($result as $model){
				$arrayCategory = getArrayCategory($model);
				array_push($arrayAll, $arrayCategory);
			}
			echo json_encode($arrayAll);
		}else{
			echo json_encode(array('id'=>'not_found'));	
		}
	}

	/*
	RETORNA TODAS AS SUBCATEGORIAS DE UMA CATEGORIA REFERENTE AO ID RECEBIDO.
	*/
	else if(strcmp('get-subcategory', $_POST['method']) == 0){
		$id_category = $_POST['data'];
		$sql = "SELECT * FROM SUBCATEGORY WHERE ID_CATEGORY ='".$id_category."'";

		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$arrayAll = array();
			foreach($result as $model){
				$arraySubcategory = getArraySubcategory($model);
				array_push($arrayAll, $arraySubcategory);
			}
			echo json_encode($arrayAll);
		}else{
			echo json_encode(array('id'=>'not_found'));	
		}
	}

	/*
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
		if($result->num_rows > 0){
			$arrayAll = array();
			foreach($result as $model){
				$arrayProfessional = getArrayPro($model);
				array_push($arrayAll, $arrayProfessional);
			}
			echo json_encode($arrayAll);
		}else{
			echo json_encode(array('id'=>'not_found'));	
		}
	}

	/*
	RETORNA O ALBUM DE UM DETERMINADO PROFISSIONAL
	*/
	else if(strcmp('get-album', $_POST['method']) == 0){

		$id_professional = $_POST['data'];
		$sql = "SELECT * FROM ALBUM WHERE ID_PROFESSIONAL = '".$id_professional."'";
		
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$arrayAll = array();
			foreach($result as $model){
				$arrayAlbum = getArrayAlbum($model);
				array_push($arrayAll, $arrayAlbum);
			}
			echo json_encode($arrayAll);
		}else{
			echo json_encode(array('id'=>'not_found'));	
		}
	}

	/*
	RETORNA APENAS UMA FOTO DO ALBUM.
	*/
	else if(strcmp('get-photo', $_POST['method']) == 0){
		$id = $_POST['data'];

		$sql = "SELECT * FROM ALBUM WHERE ID = '".$id."'";
		
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$arrayAll = array();
			foreach($result as $model){
				$arrayAlbum = getArrayAlbum($model);
				array_push($arrayAll, $arrayAlbum);
			}
			echo json_encode($arrayAll);
		}else{
			echo json_encode(array('id'=>'not_found'));	
		}
	}

	/*
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
			}
			echo json_encode($arrayProfessional);
		}else{
			echo json_encode(array('id'=>'not_found'));	
		}
	}
}else{
	/*
	ERRO GERAL
	*/
	echo json_encode(array('id'=>'0x0'));
}
 ?>