<?php

namespace App\Partner;

use App\DataBase;
use App\Institution;
use App\Http\Controllers\FileSystemController;
use App\Http\Controllers\FormatoXML;
use App\Http\Controllers\IntraData;
use App\Currencies;


class MaxPayCV extends Institution
{
    private $fields = array(9, 10, 30, 35);
    private $positions = array(1, 5, 34, 39);
    private $file = array();
    private $PAYERID = 889;
    private $APIKey = '7waonngornhjyhtf8zfaqw==';
    private $APISecret = 'adcTQ7FxQqmsnPVy0OpSirs+K7Y/ssDCF1ItXq+v1j/SjkI6IC4XaE+tggKyQrAetOrK6TMgfn30d9QflVIvXQ==';


    public function __construct()
    {
        //$this->PAYERID = $this->ID;
    }

    public function test($array, $index)
    {
        $resp = array();
        foreach ($this->fields as $key => $value) {
            $resp[$index[$value]] = $array[$index[$value]];
        }
        print_r(implode('|', $this->test2($resp, $index, 40)));
    }

    public function test2($array, $index, $size)
    {
        $resp = array();
        $i = 0;
        $this->fillFile($size);
        foreach ($this->positions as $key => $value) {
            $this->file[$value] = $array[$index[$this->fields[$i]]];
            $i++;
        }
        return $this->file;
    }

    public function fillFile($size)
    {
        for ($i = 0; $i < $size; $i++) {
            $this->file[$i] = '';
        }
    }

    public function create()
    {
        $intData = new IntraData($this->APIKey, $this->APISecret);
        $fileSystem = new FileSystemController();
        $formato = new FormatoXML();
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
            $codePais = @DataBase::verficarCountries("iso3", "iso2", str_replace('"', '', $obj[5]));
            $codeMoeda = @DataBase::verficarCurrencies($obj[32]);
            $codeMoeda = $codeMoeda->iso3;
            $codePaisRec = @DataBase::verficarCountries("iso3", "iso2", str_replace('"', '', $obj[23]));
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
            $PayerAmount = str_ireplace($remv_Amount, '.', $obj[59]);

            $commision = 2.00;

            switch (true) {

                case (floatval($PayerAmount) >= 1.00 && floatval($PayerAmount) <= 200.0):
                    $commision = 2.0;
                    break;

                case (floatval($PayerAmount) >= 200.01 && floatval($PayerAmount) <= 10000.0):
                    $commision = 1.0;
                    $commision = $PayerAmount * ($commision / 100);
                    $commision = floor($commision * 100) / 100;
                    break;

                case (floatval($PayerAmount) > 10000.0):
                    dd('ERROR: Value over 10000.0');
                    break;

                default:
                    $commision = 2.00;
                    break;
            }

            $Beneficiaryophone = '';
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
                    if (($codePaisRec == 'PRT' or $codePaisRec == 'CPV') and $obj[40] == 1) {
                        $linha = $referencia . '|' . $obj[1] . '|' . $type . '|' . '' . '|' . '' . '|' . $obj[2] . ' ' . $obj[3] . '|' . $obj[4] . '|' . $codePais . '|' . $obj[51] . '|' . '' . '|' . $DOB . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . $Client_proxy . '|' . '' . '|' . '' . '|' . $senderIdType . '|' . $obj[14] . '|' . $ExpirationDate . '|' . $obj[19] . '|' . $type . '|' . '' . '|' . '' . '|' . $codePaisRec . '|' . $obj[20] . ' ' . $obj[21] . '|' . $obj[22] . '|' . '' . '|' . $obj[50] . '|' . $Beneficiaryophone[0] . '|' . $Beneficiaryophone[1] . '|' . '' . '|' . '' . '|' . $benefIdType . '|' . $obj[37] . '|' . $expiration . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . $Account_Bank . '|' . '' . '|' . '' . '|' . '' . '|' . $IBAN . '|' . $SWIFT_BIC . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . 'BEL' . '|' . $codePaisRec . '|' . '' . '|' . '' . '|' . $num_PaymentAmount . '|' . '' . '|' . $codeMoeda . '|' . '' . '|' . '' . '|' . $commision . '|' . $PayerAmount . '|' . $CurrencyPartners . '|' . '' . '|' . '' . '|' . '' . '|' . $Sender_Partner_Identification . '|' . PHP_EOL;
                    } else if ($codeMoeda == 'EUR' and $obj[40] == 1) {
                        $linha = $referencia . '|' . $obj[1] . '|' . $type . '|' . '' . '|' . '' . '|' . $obj[2] . ' ' . $obj[3] . '|' . $obj[4] . '|' . $codePais . '|' . $obj[51] . '|' . '' . '|' . $DOB . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . $Client_proxy . '|' . '' . '|' . '' . '|' . $senderIdType . '|' . $obj[14] . '|' . $ExpirationDate . '|' . $obj[19] . '|' . $type . '|' . '' . '|' . '' . '|' . $codePaisRec . '|' . $obj[20] . ' ' . $obj[21] . '|' . $obj[22] . '|' . '' . '|' . $obj[50] . '|' . $Beneficiaryophone[0] . '|' . $Beneficiaryophone[1] . '|' . '' . '|' . '' . '|' . $benefIdType . '|' . $obj[37] . '|' . $expiration . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . $IBAN . '|' . $SWIFT_BIC . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . '' . '|' . 'BEL' . '|' . $codePaisRec . '|' . '' . '|' . '' . '|' . $num_PaymentAmount . '|' . '' . '|' . $codeMoeda . '|' . '' . '|' . '' . '|' . $commision . '|' . $PayerAmount . '|' . $CurrencyPartners . '|' . '' . '|' . '' . '|' . '' . '|' . $Sender_Partner_Identification . '|' . PHP_EOL;
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
                } else {
                    $return = $this->repair($referencia, " - Repair. Telefone do beneficiario sem traço");
                    echo $referencia . " - Repair. Telefone do beneficiario sem traço" . "<br>";
                }
            } else {
                $return = $this->repair($referencia,  " - Repair. Remessa sem o número do Beneficiario");
                echo $referencia . " - Repair. Remessa sem o número do Beneficiario" . "<br>";
            }
        }
        if ($lines) {
            $dFileCount = @DataBase::checkoutMetaValues($this->PAYERID);
            $date = date("dmY");
            $incFile = (int)$dFileCount + 1;
            $dFileCount = str_pad($incFile, 4, 0, STR_PAD_LEFT);
            $namefile = 'BEL' . $date . $dFileCount . '.txt';
            $infodata = implode('|', $infodata);
            $inout = 0;
            $idFileBackup = @DataBase::backup($this->PAYERID, $inout, $namefile, $infodata, $remCont);
            $lines = implode('', $lines);
            $upload = null;
            $upload_cv = null;
            $y = 0;
            $z = 0;
            if ($lines) {
                $path = 'Pagamentos_CV/in/';
                $upload = $fileSystem->enviar('MaxPay', $path, $namefile, $lines, $allRemsInt, $idFileBackup, $this->PAYERID);
                dump($upload);
                if ($upload) {
                    $updadeMeta = @DataBase::upMetaValues($incFile, $this->PAYERID);
                    $msg_Info['upload_IN'][$y] = "CV Upload File " . $namefile . " succeed";
                    $y++;
                    foreach ($allRemsInt as $value) {
                        $arrayProcess = array("ConfirmationReference" => "", "Note" => "PROCESS", "Reference" => $value['ref']);
                        $intD = $intData->Processing($arrayProcess);
                    }
                } else {
                    $msg_Info['errors_2'][$z] = "CV Upload File " . $namefile . " Failed ";
                    $z++;
                }
            }
        }
        var_dump($msg_Info);
    }

    public function checar()
    {
        $intData = new IntraData($this->APIKey, $this->APISecret);
        $fileSystem = new FileSystemController();
        $ftp = 'MaxPay';
        $inout = 0;
        $path = 'Pagamentos_CV/out/';
        $linesFiles = $fileSystem->receber($ftp, $path, $inout, $this->PAYERID);
        if ($linesFiles) {
            foreach ($linesFiles as $lines) {
                foreach ($lines as $line) {
                    $dataREM = @DataBase::verficarRef($line[0], $this->PAYERID);
                    if ($dataREM) {
                        if ($line[2] == -1 or $line[2] == 4 or $line[2] == 5) {
                            $arrayPaid = array("Note" => "PAID", "Reference" => $dataREM->ref);
                            $resp = $intData->Paid($arrayPaid);
                            $dataREM->status = 7;
                            $dataREM->note = 'PAID';
                            $update = @DataBase::updateU($dataREM->id, ((array)$dataREM));
                        } elseif ($line[2] == 2) {
                            $arrayCancel = array("ReasonID" => "", "Note" => "CANCEL", "Reference" => $dataREM->ref);
                            $resp = $intData->Cancel($arrayCancel);
                            $dataREM->status = 8;
                            $dataREM->note = 'CANCEL';
                            $update = @DataBase::updateU($dataREM->id, ((array)$dataREM));
                        }
                    }
                }
            }
        }
        return 'ok';
    }

    public function cancelar()
    {
        $intData = new IntraData($this->APIKey, $this->APISecret);
        $dataREM = @DataBase::verficarRef(7, $this->PAYERID);
        $arrayCancel = array("ReasonID" => "", "Note" => "CANCEL", "Reference" => $dataREM->ref);
        $resp = $intData->Cancel($arrayCancel);
        $dataREM->status = 8;
        $dataREM->note = 'CANCEL';
        $update = @DataBase::updateU($dataREM->id, ((array)$dataREM));
    }

    public function repair($ref, $msg)
    {
        $intData = new IntraData($this->APIKey, $this->APISecret);
        $array = array("RepairReasonID" => 5, "Note" => $msg, "Reference" => $ref);
        $resp = $intData->RepairOut($array);
        $dataREM = @DataBase::verficarRef($ref, $this->PAYERID);
        if ($dataREM) {
            $dataREM->status = 9;
            $dataREM->note = 'REPAIR';
            $update = @DataBase::updateU($dataREM->id, ((array)$dataREM));
        }
    }

    //    public function processo($ref)
    //    {
    //        $intData = new IntraData($this->APIKey, $this->APISecret);
    //        $dataREM = @DataBase::verficarRef(7, $this->PAYERID);
    //        $arrayProcess = array("ConfirmationReference" => "", "Note" => "PROCESS", "Reference" => $ref);
    //        $intD = $intData->Processing($arrayProcess);
    //        $dataREM->status = 4;
    //        $dataREM->note = 'PROCESS';
    //        $update = @DataBase::update($dataREM->id, $dataREM);
    //    }


}
