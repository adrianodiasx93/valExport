<?php

    $DS = DIRECTORY_SEPARATOR;

    require_once(dirname(__FILE__) .  $DS . "register.php");

    //Registro 00
    class SchoolIdentificationValidation extends Register {

        //campo 1
        function isRegisterType00($register_type) {
            if (strlen($register_type) > 2) {
                return array("status" => false,"erro" => "As linhas devem ser iniciadas com o número do registro.");
            }

            if ($register_type != 00) {
                return array("status" => false,"erro" =>
                "O registro declarado <tipo de registro> não faz parte do escopo do educacenso.");
            }

            return array("status" => true,"erro" =>"");
        }

        //campo 2
        function isInepIdValid($inep_id) {
            if ($inep_id == null) {
                return array("status" => false,"erro" =>
                "O campo Código de escola - Inep é uma informação obrigatória.");
            }

            if (strlen($inep_id) != 8) {
                return array("status" => false,"erro" =>
                "O campo Código de escola - Inep está com tamanho diferente do especificado.");
            }

            if (!is_numeric($inep_id)) {
                return array("status" => false,"erro" =>
                "O campo Código de escola - Inep foi preenchido com valor inválido");
            }

            return array("status" => true,"erro" =>"");
        }

        //campo 3
        function isManagerCPFValid($manager_cpf) {
            if ($manager_cpf == null) {
                return array("status" => false,"erro" =>
                "O campo Número do CPF do Gestor Escolar é uma informação obrigatória.");
            }

            if (strlen($manager_cpf) > 11) {
                return array("status" => false,"erro" =>
                "O campo Número do CPF do Gestor Escolar está com tamanho diferente do especificado.")
                ;
            }

            // se nao for numerico
            if (!is_numeric($manager_cpf)) {
                return array("status" => false,"erro" =>
                "O campo Número do CPF do Gestor Escolar foi preenchido com valor inválido.");
            }

            // se for 0000000000, 1111111
            if (preg_match('/^(.)\1*$/', $manager_cpf)) {
                return array("status" => false,"erro" =>
                "O campo Número do CPF do Gestor Escolar foi preenchido com valor inválido.");
            }

            if ($manager_cpf == "00000000191") {
                return array("status" => false,"erro" =>
                "O campo Número do CPF do Gestor Escolar foi preenchido com valor inválido.");
            }

            return array("status" => true,"erro" =>"");
        }

        //campo 4
        function isManagerNameValid($manager_name) {
            if ($manager_name == null) {
                return array("status" => false,"erro" =>
                "O campo Nome do Gestor Escolar é uma informação obrigatória.");
            }

            if (strlen($manager_name) > 100) {
                return array("status" => false,"erro" =>
                "O campo Nome do Gestor Escolar está maior que o especificado.");
            }

            $regex = "/^[A-Z0-9°ºª\- ]/";
            if (!preg_match($regex, $manager_name)) {
                return array("status" => false,"erro" =>
                "O campo Nome do Gestor Escolar foi preenchido com valor inválido.");
            }

            return array("status" => true,"erro" =>"");
        }

        //cargo do gestor campo 5
        function isManagerRoleValid($manager_role) {
            if ($manager_role == null) {
                return array("status" => false,"erro" =>
                "O campo Cargo do Gestor Escolar é uma informação obrigatória.");
            }

            if ($manager_role != 1 || $manager_role != 2) {
                return array("status" => false,"erro" =>
                "O campo Cargo do Gestor Escolar foi preenchido com valor inválido.");
            }

            return array("status" => true,"erro" =>"");
        }

        //address eletronico do gestor campo 6
        function isManagerEmailValid($manager_email) {
            if ($manager_email == null) {
                return array("status" => false,"erro" =>
                "O campo Endereço eletrônico (e-mail) do Gestor Escolar é uma informação obrigatória.")
                ;
            }

            if (strlen($manager_email) > 50) {
                return array("status" => false,"erro" =>
                "O campo Endereço eletrônico (e-mail) do Gestor Escolar está maior que o especificado.")
                ;
            }

            if (!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $manager_email)) {
                return array("status" => false,"erro" =>
                "O campo Endereço eletrônico (e-mail) do Gestor Escolar  foi preenchido com valor inválido.")
                ;
            }

            return array("status" => true,"erro" =>"");

        }

        //situacao de funcionamento campo 7
        function isSituationValid($situation) {
            if ($situation == null) {
                return array("status" => false,"erro" =>
                "O campo Situação de funcionamento é uma informação obrigatória.");
            }
            if ($situation != 1 || $situation != 2 || $situation != 3) {
                return array("status" => false,"erro" =>
                "O campo Situação de funcionamento foi preenchido com valor inválido.");
            }
            return array("status" => true,"erro" =>"");
        }

        //auxiliar dos campos 8 e 9
        function isDateValid($date) {

            if($date == '' || $date == null){
                return array("status" => false,"erro" =>"Data no formato incorreto");   
            }

            $data = explode('/', $date);
            $dia = $data[0];
            $mes = $data[1];
            $ano = $data[2];

            // verifica se a data é valida
            if (!checkdate($mes, $dia, $ano)) {
                return array("status" => false,"erro" =>"Data no formato incorreto");
            }

            return array("status" => true,"erro" =>"");
        }


        //campo 8 e 9
        function isSchoolYearValid($initial_date, $final_date) {
            $first_result =  $this->isDateValid($initial_date);
            $second_result = $this->isDateValid($final_date);
            if (!($first_result['status'] && $second_result['status'])) {
                return array("status" => false,"erro" =>"Data no formato incorreto");
            } else {
                $dataInicial = explode('/', $initial_date);
                $diaInicial = $dataInicial[0];
                $mesInicial = $dataInicial[1];
                $anoInicial = $dataInicial[2];

                $dataFinal = explode('/', $final_date);
                $diaFinal = $dataFinal[0];
                $mesFinal = $dataFinal[1];
                $anoFinal = $dataFinal[2];

                //A data de inicio nao pode ser inferior a 2014 nem superior a 2015
                if (!($anoInicial <= "2015" && $anoInicial >= "2014")) {
                    return array("status" => false,"erro" =>
                    "Data de inicio do ano letivo nao deve ser inferior a 2014 e superior a 2015");
                }

                //A data de termino nao pode ser inferior a data de referencia 
                //do Censoem 2015 nem superior a 2016
                if (!($anoFinal <= "2016" && $anoFinal >= "2015")) {
                    return array("status" => false,"erro" =>
                    "A data de termino do ano letivo nao pode ser inferior a 2015 nem superior a 2016")
                    ;
                }

                // se a data inicial do periodo letivo é menor que a data final
                if ($anoInicial < $anoFinal) {
                    return array("status" => true,"erro" =>"");
                } else if ($anoInicial == $anoFinal) {
                    if ($mesInicial < $mesFinal) {
                        return array("status" => true,"erro" =>"");
                    }
                    if ($mesInicial == $mesFinal) {
                        if ($diaInicial < $diaFinal) {
                            return array("status" => true,"erro" =>"");
                        }
                        if ($diaInicial >= $diaFinal)
                            return array("status" => false,"erro" =>
                        "Dia inicial é maior ou igual a Dia Final");
                    }
                    if ($mesInicial > $mesFinal) {
                        return array("status" => false,"erro" =>
                        "Mes Inicial está maior que Mes Final");
                    }
                } else {
                    return array("status" => false,"erro" =>
                    "Ano letivo inicial está maior que o ano final");
                }
            }
        }


        //campo 10
        function isSchoolNameValid($name) {
            //deve ser no minimo 4
            if (strlen($name) == 0) {
                return array("status" => false,"erro" =>
                "O campo Nome da escola é uma informação obrigatória.");
            }
            if (strlen($name) < 4) {
                return array("status" => false,"erro" =>
                "O campo Nome da escola não contém o mínimo de caracteres especificado.");
            }
            if (strlen($name) > 100) {
                return array("status" => false,"erro" =>
                "O campo Nome da escola está maior que o especificado.");
            }
            if (!preg_match('/^[a-z\d°ºª\- ]{4,28}$/i', $name)) {
                return array("status" => false,"erro" =>
                "O campo Nome da escola foi preenchido com valor inválido.");
            }

            return array("status" => true,"erro" =>"");
        }

        //campo 11
        function isLatitudeValid($latitude) {
            if (strlen($latitude) > 20) {
                return array("status" => false,"erro" =>
                "O campo Latitude está maior que o especificado.");
            }
            $regex = "/^[0-9.-]+$/";
            if (!preg_match($regex, $longitude)) {
                return array("status" => false,"erro" =>
                "O campo Laitude contém caractere(s) inválido(s).");
            }
            if ($latitude <= -33.750833 && $latitude >= 5.272222) {
                return array("status" => false,"erro" =>
                "O campo Latitude foi preenchido com valor inválido.");
            }

            return array("status" => true,"erro" =>"");
        }

        //campo 12
        function isLongitudeValid($longitude) {
            if (strlen($longitude) > 20) {
                return array("status" => false,"erro" =>
                "O campo Longitude está maior que o especificado.");
            }
            $regex = "/^[0-9.-]+$/";
            if (!preg_match($regex, $longitude)) {
                return array("status" => false,"erro" =>
                "O campo Longitude contém caractere(s) inválido(s).");
            }
            if ($longitude <= -73.992222 && $longitude >= -32.411280)
                return array("status" => false,"erro" =>
            "O campo Longitude foi preenchido com valor inválido.");

            return array("status"=>true,"erro"=>"");
        }



    //campo 13
    function isCEPValid($cep) {
        if ($cep == null) {
            return array("status" => false,"erro" =>"O campo CEP é uma informação obrigatória.");
        }
        if (count($cep) != 8) {
            return array("status" => false,"erro" =>
            "O campo CEP está com tamanho diferente do especificado.");
        }
        if (!is_numeric($cep)) {
            return array("status" => false,"erro" =>
            "O campo CEP foi preenchido com valor inválido.");
        }
        if (preg_match('/^(.)\1*$/', $cep)) {
            return array("status" => false,"erro" =>
            "O campo CEP foi preenchido com valor inválido.");
        }

        return array("status" => true,"erro" =>"");
    }

    //campo 14,campo 15,campo 16,campo 17,campo 18,campo 19,campo 20
    function isAddressValid($address, $might_be_null, $allowed_lenght) {
        $regex = "/^[0-9 a-z.,-ºª ]/";

        if (!$might_be_null) {
            if ($address == null) {
                return array("status" => false,"erro" =>"O campo de endereço não pode ser nulo.");
            }
        }
        if (strlen($address) > $allowed_lenght || strlen($address) <= 0) {
            return array("status" => false,"erro" =>
            "O campo de endereço está com tamanho incorreto.");
        }
        if (!preg_match($regex, $address)) {
            return array("status" => false,"erro" =>
            "O campo de endereço foi preenchido com valor inválido.");
        }

        return array("status" => true,"erro" =>"");
    }

    //campo 21,22,23,24,25

    function isPhoneValid($phone, $low_limit, $high_limit){

        if($phone != ""){
            return array("status" => false,"erro" =>"Telefone vazio");
        }
        if (preg_match('/^(.)\1*$/', $phone_number)) {
            return array("status" => false,"erro" =>"Telefone $phone com padrao incorreto");
        }

        $len = strlen($phone_number);
        if ($len < $allowed_lenght || $len > $high_limit) {
            return array("status" => false,"erro" =>"Telefone $phone com tamanho incorreto");
        }
        if($len == $high_limit){
           if(!(substr($phone,0, 1) != '9')){
                return array("status" => false,"erro" =>"Telefone $phone deveria iniciar com $high_limit");
           }
        }
        return array("status" => true,"erro" =>"");
    }

    function checkPhoneNumbers($ddd, $phones){
         if($ddd != ''){
            if (strlen($ddd) != 2) {
                return array("status" => false,"erro" =>"DDD incorreto");
            }
            $valid_numbers = 0;
            foreach ($phones as $key => $phone_number) {
                $result = $this->isPhoneValid($phone, 8, 9);
                if($result['status']){
                    $valid_numbers++;
                }
            }
            if($valid_numbers < 1){
                return array("status" => false,"erro" =>"Ná há numéros validos preechidos");
            }
        }
        return array("status" => true,"erro" =>"");
    }

    //campo 26
    function isEmailValid($email) {
        if (strlen($email) > 50) {
            return array("status" => false,"erro" =>"Email com tamanho invalido");
        }

        if (!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', 
                        $email)) {
            return array("status" => false,"erro" =>"Email com padrão invalido");
        }

        return array("status" => true,"erro" =>"");
    }


    //campo 28
    function isAdministrativeDependenceValid($value, $uf) {

        $result = $this->isAllowed($value, array('1', '2', '3', '4'));
        if(!$result['status']){
            return array("status"=>false,"erro"=>$result['erro']);
        }

        if($uf == '53'){
            if($value == '3'){
                return array("status" => false,"erro" =>"Dependencia Administrativa inválida");
            }
        }
        return array("status" => true,"erro" =>"");
    }

    //campo 29
    function isLocationValid($value) {
        if ($value != 1 || $value != 2) {
            return array("status" => false,"erro" =>"Lozalização inválida");
        }

        return array("status" => true,"erro" =>"");
    }

    //auxiliar nos campos 30,31,32
    function isField7And28Valid($inep_id, $schoolSituation, $dependency) {
        //campo 7 deve ser igual a 1.. Campo 28 deve ser igual a 4
        if ($schoolSituation == 1 && isSituationValid($schoolSituation) == true
                && isAdministrativeDependenceValid($inep_id, $dependency) == true && $dependency == 4) {
            return true;
        } else {
            return false;
        }
    }

    //campo 30
    function checkPrivateSchoolCategory($value, $situation, $administrative_dependence){
        if($situation == '1' && $administrative_dependence == '4'){
            $result = $this->isAllowed($value, array('1', '2', '3', '4'));
            if(!$result['status']){
                return array("status"=>false,"erro"=>$result['erro']);
            }
        }else{
            if($value != null){
                return array("status"=>false,"erro"=>"Valor $value deveria ser nulo");
            }
        }

        return array("status" => true,"erro" =>"");
    }

    //campo 31

     function isPublicContractValid($value, $situation, $administrative_dependence){
        $result = $this->isAllowed($value, array('1', '2', '3'));
        if(!$result['status']){
            return array("status"=>false,"erro"=>$result['erro']);
        }
        if(!($situation == '1' && $administrative_dependence == '4')){
            if($value != null){
                return array("status"=>false,"erro"=>"Valor $value deveria ser nulo");
            }
        }

        return array("status" => true,"erro" =>"");
    }

    //campos 32 a 36

    function isPrivateSchoolMaintainerValid($keepers,  $situation, $administrative_dependence){

        if($situation == '1' && $administrative_dependence == '4'){
            $result = $this->atLeastOne($keepers);
            if(!$result['status']){
                return array("status"=>false,"erro"=>$result['erro']);
            }
        }else{
            foreach ($keepers as $key => $value) {
                if($value != null){
                    return array("status"=>false, "erro"=>"Valor deveria ser nulo");
                }
            }
        }

        return array("status" => true,"erro" =>"");
    }

    //para os campos 37 e 38
     function isCNPJValid($cnpj, $situation, $administrative_dependence) {

        if(!($situation == '1' && $administrative_dependence == '4')){
            if($cnpj != null){
                return array("status"=>false,"erro"=>"Valor $value deveria ser nulo");
            }
        }

        if (!is_numeric($cnpj)) {
            return array("status" => false,"erro" =>"CNPJ está com padrão inválido");
        }
        if (strlen($cnpj) != 14) {
            return array("status" => false,"erro" =>"O CNPJ está com tamanho errado");
        }

        return array("status" => true,"erro" =>"");
    }

    //campo 39
    function isRegulationValid($value, $schoolSituation) {
        //campo 7 deve ser igual a 1
        if ($schoolSituation == 1) {
            if ($value != 0 || $value != 1 || $value != 2) {
                return array("status" => false,"erro" =>"Regulamentação da escola errada");
            }
        }else{
            if($value != null){
                return array("status"=>false,"erro"=>"Valor $value deveria ser nulo");
            }
        }

        return array("status" => true,"erro" =>"");
    }

    //campo 40,41 e 42
    function isOfferOrLinkedUnity($value, $InepCode, $HeadSchool, $schoolSituation,
                                  $hostedcenso_city_fk, $atualedcenso_city_fk, $hostDependencyAdm, $atualDependencyAdm, $IESCode) {
        if ($value == 1) {
            return isInepHeadSchoolValid($InepCode, $HeadSchool, $schoolSituation, $hostedcenso_city_fk, $atualedcenso_city_fk, $hostDependencyAdm, $atualDependencyAdm);
        }
        if ($value == 2) {
            return isIESCodeValid($IESCode, $schoolSituation);
        }
    }

    //41
    function inepHeadSchool($value, $offer_or_linked_unity, $current_inep_id,
                            $head_school_situation, $head_of_head_school){

        if($offer_or_linked_unity == '1'){
            if(!is_numeric($value)){
                return array("status" => false,"erro" =>"Apenas números são permitidos");
            }
            if(strlen($value) != 8){
                return array("status" => false,"erro" =>"Deve conter 8 caracteres");
            }
            if($value == $current_inep_id){
                return array("status" => false,"erro" =>"Inep ids não podem ser idênticos");
            }
            if($head_school_situation != '1'){
                return array("status" => false,"erro" =>"Escola sede não está em atividade");
            }
            if($administrative_dependence != $head_school_administrative_dependence){
                return array("status" => false,"erro" =>"Escola sede e atual diferem em dependência administrativa");
            }
            if(substr($current_inep_id, 0, 2) != substr($value, 0, 2)){
                return array("status" => false,"erro" =>"Escolas não são do mesmo estado");
            }
            if($current_inep_id == $head_of_head_school){
                return array("status" => false,"erro" =>"Escola sede não pode ter atual como sede");
            }

        }else{
            if($value != null){
                return array("status"=>false,"erro"=>"Valor $value deveria ser nulo");
            }
        }

        return array("status" => true,"erro" =>"");

    }

    //42
    function iesCode($value, $status){

        if($offer_or_linked_unity == '1'){
            if(!is_numeric($value)){
                return array("status" => false,"erro" =>"Apenas números são permitidos");
            }
            if($status != '1'){
                return array("status" => false,"erro" =>"IES deve ser existir, está ativa e ser da mesma dependência administrativa");
            }

        }else{
            if($value != null){
                return array("status"=>false,"erro"=>"Valor $value deveria ser nulo");
            }
        }

        return array("status" => true,"erro" =>"");
    }
}
?>