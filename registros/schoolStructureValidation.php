<?php
$DS = DIRECTORY_SEPARATOR;


//Validações para a tabela school_structure
require_once(dirname(__FILE__) . $DS . "register.php");


class SchoolStructureValidation extends Register{

	function __construct() {
	}

	//campo 12
	function buildingOccupationStatus($collun3, $collun8, $value){

		if($collun3 == 1){
			if(!($value == 1 || $value == 2 || $value == 3)){
				return array("status"=>false,
								"erro"=>"operation_location_building é 1. Valor $value não está enre as õpções");
			}
		}elseif($collun3 != 1 && $collun8 != 1){
			if($value != null){
				return array("status"=>false,"erro"=>"Valor $value deveria ser nulo");
			}
		}
		return array("status"=>true,"erro"=>"");
	}

	//campo 13
	function sharedBuildingSchool($collun3, $value){

		if($collun3 == 1){
			if($value == 0 || $value == 1){
				return array("status"=>true,"erro"=>"");
			}else{
				return array("status"=>false,"erro"=>"valor $value não permitido");
			}
		}else{
			if(($value != null)){
				return array("status"=>false,"erro"=>"operation_location_building não é 1. Valor $value deveria ser nulo");

			}
		}
		return array("status"=>true,"erro"=>"");
	}


	//campo 14 à 19
	function sharedSchoolInep($collun13, $inep_id, $shared_schools_inep_ids){

		if($collun13 == 1){
			foreach($shared_schools_inep_ids as $school_inep_id){
				$result = $this->isEqual(substr($inep_id, 0, 2), 
											substr($school_inep_id, 0, 2), 
											"Escolas não são do mesmo estado");
				if($result["status"])
				{	
					if(strlen($school_inep_id)){
						return array("status"=>false,"erro"=>"Menos de 8 carácteres");
					}
					if($inep_id == $school_inep_id){
						return array("status"=>false,"erro"=>"Inep id é igual");
					}
				}else{
					return array("status"=>false,"erro"=>"Não são do mesmo UF");
				}
			}
		}
		return array("status"=>true,"erro"=>"");
	}

	//campo 20
	function consumedWater($value){
		if($value == 1 || $value == 2){
			return array("status"=>true,"erro"=>"");
		}
		$value = $this->ifNull($value);
		return array("status"=>false,"erro"=>"Valor $value não está entre as opções");

	}



	function supply($supply_locations){

		$len = sizeof($supply_locations);

		$result = $this->checkRangeOfArray($supply_locations, array("0", "1"));
		if(!$result["status"]){
			return array("status"=>false,"erro"=>$result["erro"]);

		}

		$result = $this->atLeastOne($supply_locations);
		if(!$result["status"]){
			return array("status"=>false,"erro"=>$result["erro"]);
		}

		if($supply_locations[$len-1] == "1"){ //ultimo campo
			for($i = 0; $i < ($len-1); $i++){ //primeiros campos
				if($supply_locations[$i] == "1"){
					return array("status"=>false,
								"erro"=>"Já que ultimo campo 1 não pode haver outros campos marcados como 1");
				}
			}
		}
		return array("status"=>true,"erro"=>"");
	}

	//campo 69
	function schoolsCount($collun3, $value){

		if($collun3 == 1){
			$result = $this->isGreaterThan($value, "0");
			if(!$result["status"]){
				return array("status"=>false,"erro"=>$result["erro"]);
			}
		}
		return array("status"=>true,"erro"=>"");
	}


	//71 à 83
	function equipmentAmounts($amounts){

		foreach ($amounts as $key => $value) {
			if(!$value == null){
				$result = $this->isGreaterThan($value, "0");
				if(!$result["status"]){
					return array("status"=>false,"erro"=>$result["erro"]);
				}
		
				$result = $this->isGreaterThan(strlen($value), "4");
				if($result["status"]){
					return array("status"=>false,"erro"=>"Valor $value maior que 4 dígitos");
				}
			}
		}
		return array("status"=>true,"erro"=>"");
	}

	//84, 85
	function pcCount($collun82, $value){
		if($collun82 != null){
			$result = $this->isGreaterThan(strlen($value), "4");
			if($result["status"]){
				return array("status"=>false,"erro"=>"Valor $value maior que 4 dígitos");
			}
			$result = $this->isGreaterThan($value, "0");
			if(!$result["status"]){
				return array("status"=>false,"erro"=>$result["erro"]);
			}

			if($value > $collun82){
				return array("status"=>false,"erro"=>"Valor $value é maior que o permitido");
			}
			
		}else{
			if($value != null) {
				return array("status"=>false,"erro"=>"Coluna 82 é nulo. Valor $value deve ser nulo");
			}
		}
		return array("status"=>true,"erro"=>"");
		
		
	}

	//86
	function internetAccess($collun, $value){

		if($collun != null){
			if(!in_array($value, array("0", "1"))){
				$value = $this->ifNull($value);
				return array("status"=>false,"erro"=>"valor $value não permitido");
			}
		}else{
			if($value != null) {
				return array("status"=>false,"erro"=>"Coluna 82 é nulo. Valor $value deve ser nulo");

			}
		}
		return array("status"=>true,"erro"=>"");
	}

	//campo 87
	function bandwidth($collun, $value){

		if($collun == 1){
			if(!($value == "0" || $value == "1")){
				return array("status"=>false,"erro"=>"valor $value não permitido");
			}
		}
		return array("status"=>true,"erro"=>"");
	}

	//89
	function schoolFeeding($collun1028, $value, $collun206){

		if(in_array($collun1028, array("1", "2", "3"))){
			if($value == "1"){
				if($collun206 <= "0"){
					return array("status"=>false,
									"erro"=>"Ao menos um Campo 6 do registro 20 
												para esta escola deve ser 1 ou 2");
				}
			}else{
				$value = $this->ifNull($value);
				return array("status"=>false,
								"erro"=>"Valor $value deve ser 1 pois a coluna está entre os valores supostos");
			}
		}else{
			if($value != "0"){
				return array("status"=>false,
								"erro"=>"Valor $value deveria ser 0 
											pois a coluna não está entre os valores supostos");
			}
		}

		return array("status"=>true,"erro"=>"");
	}

	//90, 91
	function aee($value, $collun, $modalities, $collun2018){

		if(!in_array($value, array("0", "1", "2"))){
			return array("status"=>false, "erro"=>"Valor $value não está entre os valores permitidos");
		}else{
			if($value == "1" || $value == "2"){
				if($collun2018 <= 0){
					return array("status"=>false, 
									"erro"=>"Já que valor à $value.
												Coluna 18 de valor $collun2018 do registro 20 está incorreta");
				}
			}
			if($value == "2"){
				if($collun != 0){
					return array("status"=>false, 
									"erro"=>"Já que valor 2 a outra coluna de valor $collun deveria ser 0");
				}
				foreach ($modalities as $key => $value) {
					if($value != null){
						return array("status"=>false, "erro"=>"Valor deveria ser nulo");
					}
				}
			}
		}

		return array("status"=>true,"erro"=>"");
	}

	//92 à 95
	// falta validação 'bruta'



	function checkModalities($collun90, $collun91, $modalities, 
								$are_there_students_by_modalitie,
								$are_there_instructors_by_modalitie)
	{

		if($collun90 != 2 && $collun91 != 2){
			if(!($collun90 == 1 && $collun91 == 1)){
				$result = $this->atLeastOne($modalities);
				if(!$result["status"]){
					return array("status"=>false,"erro"=>$result["erro"]);
				}
			}
		}

		foreach ($modalities as $key => $value) {
			if($value == "1"){
				if(!$are_there_students_by_modalitie[$key]){
					return array("status"=>false,
									"erro"=>"$key é 1 e não há estudantes nessa modalidade");
				}
				if(!$are_there_instructors_by_modalitie[$key]){
					return array("status"=>false,
									"erro"=>"$key é 1 e não há instrutores nessa modalidade");
				}
			}
		}


		return array("status"=>true,"erro"=>"");
	}

	//96
	//falta validação 'bruta'
	function schoolCicle($value, $number_of_schools){
		if($number_of_schools > 0){
			if(!($value == 0 || $value == 1)){
				$value = $this->ifNull($value);
				return array("status"=>false, "erro"=>"Valor $value não permitido");
			}
		}else{
			if($value != null){
				return array("status"=>false, "erro"=>"Valor $value deveria ser nulo");
			}
		}

		return array("status"=>true,"erro"=>"");

	}

	//97
	function differentiatedLocation($collun0029, $value){

		if(!in_array($value, array("1", "2", "3", "4", "5", "6", "7"))){
			$value = $this->ifNull($value);
			return array("status"=>false,"erro"=>"Valor $value não permitido");
		}

		if($collun0029 == 1){
			if($value == 1) return array("status"=>false,
											"erro"=>"Valor $value não permitido 
													pois coluna 29 do registro é $collun0029");
		}elseif ($collun0029 == 2) {
			if($value != 1) return array("status"=>false,
											"erro"=>"Valor $value não permitido 
													pois coluna 29 do registro é $collun0029");
		}

		return array("status"=>true,"erro"=>"");
	}

	function materials($itens){

		$len = sizeof($itens);

		$result = $this->checkRangeOfArray($itens, array("0", "1"));
		if(!$result["status"]){
			return array("status"=>false,"erro"=>$result["erro"]);

		}

		$result = $this->atLeastOne($itens);
		if(!$result["status"]){
			return array("status"=>false,"erro"=>$result["erro"]);
		}

		$result = $this->exclusive($itens);
		if(!$result["status"]){
			return array("status"=>false,"erro"=>$result["erro"]);
		}

		return array("status"=>true,"erro"=>"");
	}

	//102 e 103

	function languages($collun101, $languages){

		if($collun101 != "1"){
			foreach ($languages as $key => $value) {
				if($value != null){
					return array("status"=>false, "erro"=>"Valor deveria ser nulo");
				}
			}
		}else{
			$result = $this->atLeastOne($languages);
			if(!$result["status"]){
				return array("status"=>false,"erro"=>$result["erro"]);
			}
		}

		return array("status"=>true,"erro"=>"");

	}

	//104

	function edcensoNativeLanguages($collun102, $value, $conection){

		if($collun102 != "1"){
			if($value != null){
				return array("status"=>false, "erro"=>"Valor deveria ser nulo pois coluna 102 é $collun102");
			}
		}else{
			$sql = "SELECT * FROM edcenso_native_languages WHERE id = '$value' ";
			$result = $conection->query($sql);
			$array = mysqli_fetch_all($result,MYSQLI_ASSOC);
			if(empty($array)){
				$value = $this->ifNull($value);
				return array("status"=>false,
								"erro"=>"Valor $valor não está entre os valores de edcenso_native_languages");
			}
		}

		return array("status"=>true,"erro"=>"");
	}

	//107

	function pedagogicalFormation($value, $number_of_classrooms){
		
		if(!($value == 0 || $value == 1)){
				$value = $this->ifNull($value);
				return array("status"=>false, "erro"=>"Valor $value não permitido");
		}
		
		if($number_of_classrooms == 0){
			if($value != 0) {
				$value = $this->ifNull($value);
				return array("status"=>false, "erro"=>"Valor $value não permitido");
			}
		}

		return array("status"=>true,"erro"=>"");

	}

	


}//fim de classe









