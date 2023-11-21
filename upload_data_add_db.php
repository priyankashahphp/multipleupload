<?php  
include 'config.php';
$page_title = 'Fee Assignment';
$page_url = 'upload_data_add';

$submitErr = 0;

    $filename = $_FILES['file']['tmp_name'];
    $filename_n = $_FILES['file']['name'];
    $handleFile = fopen($filename, "r");
    $line = fgetcsv($handleFile);
    $numcols = count($line);
    
    if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != ''){
        
        //$extension = end((explode(".", $_FILES['file']['name'])));
		
		$tmp = explode('.', $filename_n);
		$file_extension = end($tmp);
		//print_r($file_extension); exit;
        if($file_extension != 'csv'){
            $data['result']=0;
            $data['msg'] ="Please uplaod only csv file";
            $data['error_data'][] = ['e'=>'file','m'=>'Required'];
        }else{
            $c = 2;
            
            while(($fdata = fgetcsv($handleFile,100000000, ",")) !== false)
            {
              // print_r($fdata); exit;
                $tranDate= $fdata[1];
                $acadYear = $fdata[2];
                $session = $fdata[3];   
                $alocated_category = $fdata[4];
                $voucher_type = $fdata[5];
                $voucherno = $fdata[6];
                $rollno = $fdata[7];
                $admno = $fdata[8];
                $status = $fdata[9];
                $feecategory = $fdata[10];
                $faculty = $fdata[11];
                $program = $fdata[12];
                $department = $fdata[13];
                $batch = $fdata[14];
                $receipt_no = $fdata[15];
                $feehead = $fdata[16];
                $dueamount = $fdata[17];
                $paidamount = $fdata[18];
                $concessionamount = $fdata[19];
                $Scholarshipamount = $fdata[20];
                $reverseconcessionamount = $fdata[21];
                $wrteoffamount = $fdata[22];
                $adjustedamunt = $fdata[23];
                $RefundAmount = $fdata[24];
                $FundTranCferAmount = $fdata[25];
                $remark = $fdata[26];
                
                /*print_r("INSERT INTO `alldatatbl`( `date`, `academic_year`, `session`, `alloted_category`, `voucher_type`, `voucher_no`, `roll_no`, `admno`, `status`, `fee_category`, `faculty`, `Program`, `Department`, `batch`, `receipt_no`, `fee_head`, `due_amount`, `paid_amount`, `concession_amount`, `scholarship_amount`, `reverse_concession_amount`, `write_off_amount`, `adjusted_amount`, `refund_amount`, `fund_trancfer_amount`, `remarks`) VALUES ('$tranDate','$acadYear','$session','$alocated_category','$voucher_type','$voucherno','$rollno','$admno','$status','$feecategory','$faculty','$program','$department','$batch','$receipt_no','$feehead','$dueamount','$paidamount','$concessionamount','$Scholarshipamount','$reverseconcessionamount','$wrteoffamount','$adjustedamunt','$RefundAmount','$FundTranCferAmount','$remark')");exit;*/
                $db->query("INSERT INTO `alldatatbl`( `date`, `academic_year`, `session`, `alloted_category`, `voucher_type`, `voucher_no`, `roll_no`, `admno`, `status`, `fee_category`, `faculty`, `Program`, `Department`, `batch`, `receipt_no`, `fee_head`, `due_amount`, `paid_amount`, `concession_amount`, `scholarship_amount`, `reverse_concession_amount`, `write_off_amount`, `adjusted_amount`, `refund_amount`, `fund_trancfer_amount`, `remarks`)
                 VALUES ('$tranDate','$acadYear','$session','$alocated_category','$voucher_type','$voucherno','$rollno','$admno','$status','$feecategory','$faculty','$program','$department','$batch','$receipt_no','$feehead','$dueamount','$paidamount','$concessionamount','$Scholarshipamount','$reverseconcessionamount','$wrteoffamount','$adjustedamunt','$RefundAmount','$FundTranCferAmount','$remark')");

                $btn = $db->query("select title,id from branches where title='$faculty'");
                if(mysqli_num_rows($btn) > 0 || $faculty == ''){
                    $btnDT = $btn->fetch_assoc();
                    $brid = $btnDT['id'];
                }else{
                    $db->query("INSERT INTO `branches`(`title`) VALUES ('$faculty')");
                    $brid = $db->insert_id;
                }
                
                $fctn = $db->query("select fee_category,id from feecategory where fee_category='$feecategory' and br_id='$brid'");
                if(mysqli_num_rows($fctn) > 0 || $feecategory == ''){
                    $fctnDT = $fctn->fetch_assoc();
                    $feecatid = $fctnDT['id'];
                }else{
                    $db->query("INSERT INTO `feecategory`(`fee_category`, `br_id`) VALUES ('$feecategory','$brid')");
                    $feecatid = $db->insert_id;
                }

                $feetyctn = $db->query("select collectionhead,id from feecollectiontye where  br_id='$brid'");
                if(mysqli_num_rows($feetyctn) > 0 || $feecategory == ''){
                    $feetyctnDT = $feetyctn->fetch_assoc();
                    $feecatyid = $feetyctnDT['id'];
                    $collectionhead = $feetyctnDT['collectionhead'];
                    $moduleiq = $db->query("select moduleID from module where module='$collectionhead'");
                    $moduleDT = $moduleiq->fetch_assoc();
                    $moduleid = $moduleDT['moduleID'];

                }

                //feecollectiontype table
                

                $sqtn = 0;
                $sef = $db->query("select f_name,seq_id from feetype where f_name='$feehead'");
                if(mysqli_num_rows($sef) > 0){
                    $sefDT = $sef->fetch_assoc();
                    $sqlncnoid = $sefDT['seq_id'];
                }else{
                    $sqlcl = $db->query("SELECT MAX(seq_id) FROM feetype");
                    $sqlclDT = $sqlcl->fetch_assoc();
                    $sqlncnoid = $sqlclDT['seq_id'] + 1;
                }

                $db->query("INSERT INTO `feetype`(`fee_category`, `f_name`, `collection_id`, `br_id`, `seq_id`, `fee_type_ledger`, `fee_headtyye`) 
                VALUES ('$feecatid','$feehead','$feecatyid','$brid','$sqlncnoid','$feehead','$moduleid')");
                $ftypid = $db->insert_id;
                //
                
                if($voucher_type != 'due' && $dueamount != '0' ){
                    $entrymodeno = '0';
                    $crdr = 'D';
                    $adamount = $dueamount;
                }elseif($voucher_type != 'REVDUE' && $wrteoffamount != '0'){
                    $entrymodeno = '12';
                    $crdr = 'C';
                    $adamount = $dueamount;
                }elseif($voucher_type != 'SCHOLARSHIP' && $Scholarshipamount != '0'){
                    $entrymodeno = '15';
                    $crdr = 'C';
                    $adamount = $dueamount;
                }elseif($voucher_type != 'REVCONCESSION' && $reverseconcessionamount != '0'){
                    $entrymodeno = '16';
                    $crdr = 'D';
                    $adamount = $Scholarshipamount;
                }elseif($voucher_type != 'REVCSCHOLARSHIP' && $reverseconcessionamount != '0'){
                    $entrymodeno = '16';
                    $crdr = 'D';
                    $adamount = $reverseconcessionamount;
                }elseif($voucher_type != 'concession' && $concessionamount != '0'){
                    $entrymodeno = '15';
                    $crdr = 'C';
                    $adamount = $concessionamount;
                }elseif($voucher_type != 'RCPT' && $paidamount != '0'){
                    $entrymodeno = '0';
                    $crdr = 'C';
                    $adamount = $paidamount;
                }elseif($voucher_type != 'REVRCPT' && $paidamount != '0'){
                    $entrymodeno = '0';
                    $crdr = 'D';
                    $adamount = $paidamount;
                }elseif($voucher_type != 'JV' && $adjustedamunt != '0'){
                    $entrymodeno = '14';
                    $crdr = 'C';
                    $adamount = $adjustedamunt;
                }elseif($voucher_type != 'REVJV' && $adjustedamunt != '0'){
                    $entrymodeno = '14';
                    $crdr = 'D';
                    $adamount = $adjustedamunt;
                }elseif($voucher_type != 'PMT' && $RefundAmount != '0'){
                    $entrymodeno = '1';
                    $crdr = 'D';
                    $adamount = $RefundAmount;
                }elseif($voucher_type != 'Fundtranfer' && $FundTranCferAmount != '0'){
                    $entrymodeno = '1';
                    $crdr = 'positive and negative';
                    $adamount = $FundTranCferAmount;
                }else{
                    $entrymodeno = '';
                    $crdr = '';
                    $adamount = '';
                }

                $entymd= $db->query("select * from  entrymode where entry_modename='$voucher_type' and crdr='$crdr' and entrymodeno='$entrymodeno'");
                if(mysqli_num_rows($entymd) > 0){

                }else{
                $db->query("INSERT INTO `entrymode`(`entry_modename`, `crdr`, `entrymodeno`) VALUES ('$voucher_type','$crdr','$entrymodeno')");
                }
                $trnsid = uniqid();
                if($voucher_type == 'due' || $voucher_type == 'concession' || $voucher_type == 'SCHOLARSHIP' || $voucher_type == 'REVDUE' || $voucher_type == 'REVCONCESSION' || $voucher_type == 'REVCSCHOLARSHIP'){
                    if($voucher_type == 'concession'){
                        $tyofcon = '1';
                    }elseif($voucher_type == 'SCHOLARSHIP'){
                        $tyofcon = '2';
                    }else{
                        $tyofcon = 'null';
                    }

                    $typofcon = 
                    $trns =  $db->query("INSERT INTO `financial_trans`(`modleid`, `tranid`, `admno`, `amount`, `crdr`, `tranDate`, `acadYear`, `entrymode`, `voucherno`, `brid`, `typeofconcession`) 
                    VALUES ('$moduleid','$trnsid','$admno','$adamount','$crdr','$tranDate','$acadYear','$entrymodeno','$voucherno','$brid',' $tyofcon')");
                    if($trns === true){
                        $trndid = $db->insert_id;
                        $db->query("INSERT INTO `financial_transetail`(`financialTranID`, `moduleID`, `amont`, `headID`, `crdr`, `brid`, `head_name`) 
                        VALUES ('$trndid','$moduleid','$adamount','$ftypid','$crdr','$brid','$feehead')");
                    }
                }else{
                    if($voucher_type == 'RCPT'){
                        $tyofcon = '0';
                    }elseif($voucher_type == 'REVRCPT'){
                        $tyofcon = '1';
                    }elseif($voucher_type == 'JV'){
                        $tyofcon = '0';
                    }elseif($voucher_type == 'REVJV'){
                        $tyofcon = '1';
                    }elseif($voucher_type == 'PMT'){
                        $tyofcon = '0';
                    }elseif($voucher_type == 'REVPMT'){
                        $tyofcon = '1';
                    }else{
                        $tyofcon = 'null';
                    }

                    $comfecol = $db->query("INSERT INTO `common_fee_collection`(`moduleID`, `transID`, `admno`, `rollno`, `amount`, `brid`, `acadamicYear`, `financialYear`,`displayreceiptno`,`entrymode`,`paid_date`,`inactive`) 
                VALUES ('$moduleid','$trnsid','$admno','$rollno','$adamount','$brid','$acadYear','$session','$receipt_no','$entrymodeno','$tranDate','$tyofcon')");
                $recid = $db->insert_id;
                
                //
                $db->query("INSERT INTO `common_fee_collection_headwise`(`moduleID`, `receiptID`, `headID`, `headName`, `brid`, `amount`) 
                VALUES ('$moduleid','$recid','$ftypid','$feehead','$brid','$adamount')");
                }
                
				$c++;
            }
            
            $data['result']=1;
            $data['msg'] ="File uploaded successfully!";
            $data['route'] =$page_url;
            $data['error_data'][] = ['e'=>'file','m'=>'Required'];
            
        }
    } else{
        $data['result']=0;
        $data['msg'] ="Required field are missing ";
        $data['error_data'][] = ['e'=>'file','m'=>'Required'];
        $data['file'] ='';
    }
	echo json_encode($data);
    

 ?>