<?php
class CodeController extends Controller
{
    public function checkAction($query = null)
    {
        if ($this->isAjax()) {
            $res = [];
            if (!empty($_POST['code']) && !empty($_POST['summ'])) {
                $isValid  = true;
                $code     = $_POST['code'];
                $coupons  = $this->model->loadParams(); // move to model, call from findCouponKey?
                $coupKey  = $this->model->findCouponKey($coupons, $code);
                if ($coupKey !== false) {
                    $curCoupon = $coupons[$coupKey];
                    $validFor  = $curCoupon['Valid for'];
                    $startTime = $curCoupon['Start'];
                    $oneTime   = false;
                    $isValid   = $this->model->compareDate($validFor, $startTime);
                    if (!$isValid) {
                        $res = ['error'=>'Not valid!'];
                    }
                    if ($validFor === 'one') {
                        $oneTime = true;
                    }
                    // Calculate price
                    if ($isValid) {
                        $summ    = $_POST['summ'];
                        $type    = $curCoupon['Kind'];
                        $value   = $curCoupon['Value'];
                        $min     = $curCoupon['Minimum'];
                        $amntRes = $this->model->calculatePrice($type, $value, $summ, $min);
                        $res     = ['success' => 'true', 'summ' => $amntRes['summ'], 'oneTime' => $oneTime, 'msg' => $amntRes['msg']];
                    }
                    
                } else {
                    $isValid = false;
                    $res = ['error'=>'Not valid!'];
                }
            } else {
                $res = ['error'=>'No data!'];
            }
            $this->getResult($res);    
        }
    }
    
    private function commentCode()
    {
        
    }
    
    protected function getResult($res = null) {
        echo json_encode($res);
    }
}