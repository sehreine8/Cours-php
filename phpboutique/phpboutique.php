<?php 
//Fonctions Access aux donnees
   function selectClients():array {
    return
        [
            [
            "nom"=>"Wane",
            "prenom"=>"Baila",
            "telephone"=>"777661010",
            "adresse"=>"FO",
            "dette"=>[],

            ],
            [
            "nom"=>"Wane1",
            "prenom"=>"Baila1",
            "telephone"=>"777661011",
            "adresse"=>"FO1",
            "dette"=>[],

            ]
        ];


   }

   function selectClientByTel(array $clients,string $tel):array|null {
        foreach ($clients as  $client) {
            if ($client["telephone"] == $tel) {
               return $client;
            }
        }
        return null;
   }

   function insertClient(array &$tabClients,$client):void {
          // array_push($tabClients,$client);
           $tabClients[]=$client;
    }




//Fonctions Services ou Use Case  ou Metier
  function  enregistrerClient(array &$tabClients,array $client):bool{
     $result=  selectClientByTel($tabClients,$client["telephone"]);
     if ($result==null) {
        insertClient($tabClients,$client);
        return true;
     }
     return false;
  }

  function listerClient():array{
      return selectClients();
  }


function estVide(string $value):bool{
    //$value=="" ou empty($value)
    return empty($value);
}




//Fonctions Presentation
function saisieChampObligatoire(string $sms):string{
    do {
        $value= readline($sms);
    } while (estVide($value));
   return $value;
}
function telephoneIsUnique(array $clients,string $sms):string{
    do {
        $value= readline($sms);
    } while (estVide($value) || selectClientByTel($clients,$value)!=null);
    return $value;
   
}

function afficheClient(array $clients):void{
    if (count($clients)==0) {
        echo "Pas de client a affiche";
    }else {
        foreach ($clients as  $client) {
            echo"\n-----------------------------------------\n";
            echo "Telephone : ". $client["telephone"]."\t";
            echo "Nom : ". $client["nom"]."\t";
            echo "Prenom : ". $client["prenom"]."\t";
            echo "Adresse : ". $client["adresse"]."\t";
      }
    }
    
}



function saisieClient(array $clients):array{
    return [
        "telephone"=>telephoneIsUnique($clients,"Entrer le Telephone: "),
         "nom"=>saisieChampObligatoire(" Entrer le Nom: "),
         "prenom"=>saisieChampObligatoire(" Entrer le Prenom: "),
         "adresse"=>saisieChampObligatoire(" Entrer l'Adresse: "),
    ] ; 
}
function menu():int{
    echo "
     1.Ajouter client \n
     2.Lister les clients\n 
     3.Rechercher client par telephone\n
     4.Enregistrer une dette\n
     5.Lister les dettes d'un client\n
     6.Payer une dette\n  
     7.Quitter\n";
    return (int)readline(" Faites votre choix: ");
}
function ref_id(){
    $ref = rand(1000, 9999); // Génère un nombre aléatoire entre 1000 et 9999
    echo $ref;
}

function saisieDette(array $client):array{
    return [
        "Montant"=>saisieChampObligatoire(" Entrer le Montant: "),
        "Date"=>saisieChampObligatoire(" Entrer le Date: "),
        "Montant_versé"=>saisieChampObligatoire(" Entrer le montant versé: "),
        "ref"=>ref_id()
    ];
}

function listerDette(array $clients, string $Telephone):void{
    echo"\n-----------------------------------------\n";
    echo "Telephone : ". $client["telephone"]."\t";
    echo "Nom : ". $client["nom"]."\t";
    echo "Prenom : ". $client["prenom"]."\t";
    echo "Adresse : ". $client["adresse"]."\t";
    echo "dette : ". $client["dette"]."\t";
}




function principal(){
   $clients= selectClients();
   do {
      $choix= menu();
      switch ($choix) {
       case 1:
        $client=saisieClient($clients);
       if (enregistrerClient($clients,  $client)) {
           echo"Client Enregistrer avec success!!!!! \n";
       }else {
            echo"Le numero Telephone  existe deja!!!!! \n";
       } 
       break;
       case 2:
        afficheClient($clients);
       break;
       case 3:
        $num=readline("Saisissez le numéro de téléphone:\n");
        $existe=selectClientByTel($clients,$num);
        if ($existe==null){
            echo"Le client recherché n'existe pas!!!!";
        }
        else{
            echo"Client trouvé!!!!!!";
            echo"\n-----------------------------------------\n";
            echo "Telephone : ". $existe["telephone"]."\t";
            echo "Nom : ". $existe["nom"]."\t";
            echo "Prenom : ". $existe["prenom"]."\t";
            echo "Adresse : ". $existe["adresse"]."\t";

        }
        
       break;
       case 4:
           do{
                $rep= readline("Voulez-vous enregistrer une nouvelle dette:\n");
                if($rep=="oui" || $rep=="Oui"){
                    $telephone=readline("Saisissez un numéro de téléphone existant:\n");
                    $present=selectClientByTel($clients,$telephone);
                    if($present==null){
                        echo"Le client n'existe pas!!!";
                    }
                    else{
                        do{
                            $dette= saisieDette();
                            $rep1=readline("Voulez-vous saisir une autre dette pour ce client?\n");
                        }while($rep1=="Oui" || $rep1=="oui");

                    }
                }
               
            }while($rep=="Oui" || $rep=="oui");

        break;
        case 5:
            $Telephone=readline("Saisissez un numéro de téléphone existant:\n");
            $existant=selectClientByTel($clients,$Telephone);
            if($existant==null){
                echo"Le client n'existe pas!!!";
            }
            else{
                if (!empty($client["dette"])){
                    foreach ($clients as  $client) {
                        echo"\n-----------------------------------------\n";
                        echo "Telephone : ". $client["telephone"]."\n";
                        echo "Nom : ". $client["nom"]."\n";
                        echo "Prenom : ". $client["prenom"]."\n";
                        echo "Adresse : ". $client["adresse"]."\n";
                        foreach ($client["dette"] as $index=>$dette){
                            echo "ref: ". $dette["ref"]."\n";
                            echo "Montant : ". $dette["Montant"]."\n";
                            echo "Date : ". $dette["Date"]."\n";
                            echo "Montant versé : ". $dette["Montant_versé"]."\n";
                        } 
                    }
                }
                else{
                    echo"Ce client n'a aucune dette\n";
                }
            }





        default:
          echo "Veullez faire un bon choix: ";
           break;
      }

   } while ($choix!=7);
}
principal();