<?php

namespace App\Partner;

use App\Backup;
use App\DataBase;
use App\Institution;
use App\Http\Controllers\FileSystemController;
use App\Http\Controllers\FormatoXML;
use App\Http\Controllers\IntraData;
use App\Currencies;


class MaxPay extends Institution
{
    private $PAYERID = 883;
    private $APIKey = 'dwuemvp2pr/0fhzhaoypxa==';
    private $APISecret = 'jn4Sy2QDwpx481HYvsLGdeA6Q3LRAqSj7sFkArt6xVsKXquTd5cyyTi0QJg+qoNlDSxoQPjGXZ65n7l6VtCGwA==';

    public function create()
    {
        $intData = new IntraData($this->APIKey, $this->APISecret);
        $fileSystem = new FileSystemController();
        $formato = new FormatoXML();
        $db = new DataBase();
        $msg_Info = array();
        $lines = array();
        $allRemsInt = array();
        $listRem = $intData->RemitanceAll();

        $remCont = 0;
        $infodata = array();


        foreach ($listRem as $rem) {
            $obj = $formato->formatObjeto($rem);
            echo 'Referencias das remessas : ' . $obj[0] . '<br>';
            $pin = $obj[48];
            $codePais = $db->verficarCountries("iso3", "iso2", str_replace('"', '', $obj[5]));
            $codeMoeda = $db->verficarCurrencies($obj[32]);
            $codeMoeda = $codeMoeda->iso3;
            $codePaisRec = $db->verficarCountries("iso3", "iso2", str_replace('"', '', $obj[23]));
            $expiration = '';
            $referencia = $obj[0];
            $type = 'S';
            $Client_proxy = '';
            $Sender_Partner_Identification = "Belmoney";
            $remv_Amount = array(',');
            $num_PaymentAmount = $obj[33];
            $num_PaymentAmount = str_ireplace($remv_Amount, '.', $num_PaymentAmount);
            $Account_Bank = $obj[28];
            $SWIFT_BIC = trim($obj[29]);
            $IBAN = trim($obj[31]);

            $CurrencyPartners = 'EUR';
            $DOB = date('d-m-Y', strtotime($obj[9]));
            $ExpirationDate = date('d-m-Y', strtotime($obj[15]));
            $Beneficiaryophone = '';
            $PayerAmount = str_ireplace($remv_Amount, '.', $obj[59]);

            $commision = 2.00;

            switch (true) {

              case (floatval($PayerAmount) >= 400.01 && floatval($PayerAmount)<=5000.0):
                    $commision = 0.5;
                    $commision = $PayerAmount * ($commision / 100);
                    $commision = floor($commision * 100) / 100;
                  break;

              case (floatval($PayerAmount) >= 5000.01 && floatval($PayerAmount)<=10000.0):
                        $commision = 1.0;
                        $commision = $PayerAmount * ($commision / 100);
                        $commision = floor($commision * 100) / 100;
                      break;

              case (floatval($PayerAmount) > 10000.0):
                                dd('ERROR: Value over 10000.0');
                              break;
              case (floatval($PayerAmount) < 15.0):
                               dd('ERROR: Value under 15.0');
                              break;

              default:
                $commision = 2.00;
                break;
            }


            if ($obj[25]) {
                $Beneficiaryophone = $obj[25];
            } else if ($obj[26]) {
                $Beneficiaryophone = $obj[26];
            }
            if ($Beneficiaryophone) {
                if (strstr($Beneficiaryophone, '-')) {
                    $Beneficiaryophone = explode('-', $Beneficiaryophone);
                    $benefIdType = $obj[36];
                    if ($benefIdType == 25 or $benefIdType == 27) {
                        $benefIdType = 'TR';
                    } elseif ($benefIdType == 7 or $benefIdType == 26) {
                        $benefIdType = 'PASS';
                    } else {
                        $benefIdType = 'BI';
                    }
                    $senderIdType = $obj[13];
                    if ($senderIdType == 25 or $senderIdType == 27) {
                        $senderIdType = 'TR';
                    } elseif ($senderIdType == 7 or $senderIdType == 26) {
                        $senderIdType = 'PASS';
                    } elseif ($senderIdType == 0) {
                        $senderIdType = '';
                    } elseif ($senderIdType) {
                        $senderIdType = 'BI';
                    }
                    if ($codePaisRec == 'PRT' and $obj[40] == 1) {
                        $linha = $referencia . '|' . $obj[1] . '|' . $type . '|' . '' . '|' . '' . '|' . $obj[2] . ' ' . $obj[3] . '|' . $obj[4] . '|' . $codePais . '|' . $obj[51] . '|' . '' . '|' . $DOB . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . $Client_proxy . '|' . '' . '|' . '' . '|' . $senderIdType . '|' . $obj[14] . '|' . $ExpirationDate . '|' . $obj[19] . '|' . $type . '|' . '' . '|' . '' . '|' . $codePaisRec . '|' . $obj[20] . ' ' . $obj[21] . '|' . $obj[22] . '|' . '' . '|' . $obj[50] . '|' . $Beneficiaryophone[0] . '|' . $Beneficiaryophone[1] . '|' . '' . '|' . '' . '|' . $benefIdType . '|' . $obj[37] . '|' . $expiration . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . $Account_Bank . '|' . $IBAN . '|' . $SWIFT_BIC . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . 'BEL' . '|' . $codePaisRec . '|' . '' . '|' . '' . '|' . $num_PaymentAmount . '|' . '' . '|' . $codeMoeda . '|' . '' . '|' . '' . '|' . $commision . '|' . $PayerAmount . '|' . $CurrencyPartners . '|' . '' . '|' . '' . '|' . '' . '|' . $Sender_Partner_Identification . '|' . PHP_EOL;

                    } else if ($codeMoeda == 'EUR' and $obj[40] == 1) {
                        $linha = $referencia . '|' . $obj[1] . '|' . $type . '|' . '' . '|' . '' . '|' . $obj[2] . ' ' . $obj[3] . '|' . $obj[4] . '|' . $codePais . '|' . $obj[51] . '|' . '' . '|' . $DOB . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . $Client_proxy . '|' . '' . '|' . '' . '|' . $senderIdType . '|' . $obj[14] . '|' . $ExpirationDate . '|' . $obj[19] . '|' . $type . '|' . '' . '|' . '' . '|' . $codePaisRec . '|' . $obj[20] . ' ' . $obj[21] . '|' . $obj[22] . '|' . '' . '|' . $obj[50] . '|' . $Beneficiaryophone[0] . '|' . $Beneficiaryophone[1] . '|' . '' . '|' . '' . '|' . $benefIdType . '|' . $obj[37] . '|' . $expiration . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . $obj[28] . '|' . $IBAN . '|' . $SWIFT_BIC . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . 'BEL' . '|' . $codePaisRec . '|' . '' . '|' . '' . '|' . $num_PaymentAmount . '|' . '' . '|' . $codeMoeda . '|' . '' . '|' . '' . '|' . $commision . '|' . $PayerAmount . '|' . $CurrencyPartners . '|' . '' . '|' . '' . '|' . '' . '|' . $Sender_Partner_Identification . '|' . PHP_EOL;

                    } else {
                        $linha = $referencia . '|' . $obj[1] . '|' . $type . '|' . '' . '|' . '' . '|' . $obj[2] . ' ' . $obj[3] . '|' . $obj[4] . '|' . $codePais . '|' . $obj[51] . '|' . '' . '|' . $DOB . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . $Client_proxy . '|' . '' . '|' . '' . '|' . $senderIdType . '|' . $obj[14] . '|' . $ExpirationDate . '|' . $obj[19] . '|' . $type . '|' . '' . '|' . '' . '|' . $codePaisRec . '|' . $obj[20] . ' ' . $obj[21] . '|' . $obj[22] . '|' . '' . '|' . $obj[50] . '|' . $Beneficiaryophone[0] . '|' . $Beneficiaryophone[1] . '|' . '' . '|' . '' . '|' . $benefIdType . '|' . $obj[37] . '|' . $expiration . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . 'BEL' . '|' . $codePaisRec . '|' . '' . '|' . '' . '|' . $num_PaymentAmount . '|' . '' . '|' . $codeMoeda . '|' . '' . '|' . '' . '|' . $commision . '|' . $PayerAmount . '|' . $CurrencyPartners . '|' . '' . '|' . '' . '|';
                        if ($obj[40] == 3 or $obj[40] == 2) {
                            $linha = $linha . $pin . '|' . $Sender_Partner_Identification . '|' . PHP_EOL;
                        } else {
                            $linha = $linha . '' . '|' . $Sender_Partner_Identification . '|' . PHP_EOL;
                        }
                    }
                    $remCont++;
                    array_push($lines, $linha);
                    $note = 2; //$obj[38];
                    $refs = array('PIN' => $obj[48], 'ref' => $referencia, 'refPIN' => '', 'status' => 4, 'payingAmount' => $obj[33], 'currency' => (@Currencies::where('iso3', $obj[32])->first()->id), 'country' => '', 'paymentType' => $obj[40], 'sender' => $obj[2] . ' ' . $obj[55] . ' ' . $obj[3], 'senderID' => $obj[1], 'senderIDLot' => '', 'senderCurrency' => '', 'senderCountry' => $obj[5], 'senderIDDoc' => $obj[14], 'receiver' => $obj[20] . ' ' . $obj[56] . ' ' . $obj[21], 'receiverBank' => $obj[28], 'receiverAgency' => $obj[52], 'receiverBankAccount' => $obj[31], 'receiverInstitution' => 1, 'receiverAccountType' => $obj[30], 'receiverIDLot' => '', 'receiverID' => $obj[19], 'receiverCurrency' => $obj[32], 'receiverCountry' => $obj[23], 'receiverIDDoc' => $obj[37], 'user' => ' ', 'institution' => $this->PAYERID, 'note' => $note, 'CWDtime' => date("Y-m-d H:m:s"));
                    array_push($allRemsInt, $refs);
                    $obj = implode(',', $obj);
                    array_push($infodata, $obj);
                }else{
                    $this->repair($referencia, " - Repair. Telefone do beneficiario sem traço");
                    echo $referencia . " - Repair. Telefone do beneficiario sem traço" . "<br>";
                }
            } else {
                $this->repair($referencia, " - Repair. Telefone do beneficiario sem traço");
                echo $referencia . " - Repair. Remessa sem o número do Beneficiario" . "<br>";
            }
        }
        if ($lines) {
            $dFileCount = $db->checkoutMetaValues($this->PAYERID);
            $date = date("dmY");
            $incFile = (int)$dFileCount + 1;
            $dFileCount = str_pad($incFile, 4, 0, STR_PAD_LEFT);
            $namefile = 'BEL' . $date . $dFileCount . '.txt';
            $infodata = implode('|', $infodata);
            $inout = 0;
            $idFileBackup = $db->backup($this->PAYERID, $inout, $namefile, $infodata, $remCont);
            $lines = implode('', $lines);
            $upload = null;
            $upload_cv = null;
            $y = 0;
            $z = 0;
            if($lines) {
                $path = 'Pagamentos/in/';
                $upload = $fileSystem->enviar('MaxPay', $path, $namefile, $lines, $allRemsInt, $idFileBackup, $this->PAYERID);
                if ($upload) {
                    $db->upMetaValues($incFile, $this->PAYERID);
                    $msg_Info['upload_IN'][$y] = "PT Upload File " . $namefile . " succeed";

                    foreach ($allRemsInt as $value) {
                        $arrayProcess = array("ConfirmationReference" => "", "Note" => "PROCESS", "Reference" => $value['ref']);
                        $intData->Processing($arrayProcess);
                    }
                } else {
                    $msg_Info['errors_2'][$z] = "PT Upload File " . $namefile . " Failed ";
                }
            }
        }
        var_dump($msg_Info);

    }

    public function checar()
    {
        $intData = new IntraData($this->APIKey, $this->APISecret);
        $fileSystem = new FileSystemController();
        $db = new DataBase();
        $ftp = 'MaxPay';
        $inout = 0;
        $path = 'Pagamentos/out/';

        $linesFiles = $fileSystem->receber($ftp, $path, $inout, $this->PAYERID);
        if ($linesFiles) {
            foreach ($linesFiles as $lines) {
                foreach ($lines as $line) {
                    $dataREM = $db->verficarRef($line[0], $this->PAYERID);
                    if ($dataREM) {
                        if ($line[2] == -1 or $line[2] == 4 or $line[2] == 5) {
                            $arrayPaid = array("Note" => "PAID", "Reference" => $dataREM->ref);
                            $resp = $intData->Paid($arrayPaid);
                            $dataREM->status = 7;
                            $dataREM->note = 'PAID';
                            $update = $db->updateU($dataREM->id,((Array)$dataREM));
                        } elseif ($line[2] == 2) {
                            $arrayCancel = array("ReasonID" => "", "Note" => "CANCEL", "Reference" => $dataREM->ref);
                            $resp = $intData->Cancel($arrayCancel);
                            $dataREM->status = 8;
                            $dataREM->note = 'CANCEL';
                            $update = $db->updateU($dataREM->id,((Array)$dataREM));
                        }
                    }
                }
            }
        }
        return 'ok';
    }

    public function cancelar()
    {
        $db = new DataBase();
        $intData = new IntraData($this->APIKey, $this->APISecret);
        $dataREM = $db->verficarRef(6, $this->PAYERID);
        $arrayCancel = array("ReasonID" => "", "Note" => "CANCEL", "Reference" => $dataREM->ref);
        $resp = $intData->Cancel($arrayCancel);
        $dataREM->status = 8;
        $dataREM->note = 'CANCEL';
        $update = $db->updateU($dataREM->id, $dataREM);
    }
    //
    public function repair($ref, $msg)
    {
        $db = new DataBase();
        $intData = new IntraData($this->APIKey, $this->APISecret);
        $array = array("RepairReasonID" => 5, "Note" => $msg, "Reference" => $ref);
        $resp = $intData->RepairOut($array);
        $dataREM = $db->verficarRef($ref, $this->PAYERID);
        if ($dataREM) {
            $dataREM->status = 9;
            $dataREM->note = 'REPAIR';
            $update = $db->updateU($dataREM->id, ((Array)$dataREM));
        }
    }

//
//    public function paid()
//    {
//        $intData = new IntraData($this->APIKey, $this->APISecret);
    //$dataREM = @DataBase::verficarRef(7, $this->PAYERID);
//        $arrayPaid = array("Note" => "PAID", "Reference" => 15);
//        $resp = $intData->Paid($arrayPaid);
    //$dataREM->status = 7;
    //$dataREM->note = 'PAID';
    //$update = @DataBase::update($dataREM->id, $dataREM);
//        var_dump($resp);
//    }


}
