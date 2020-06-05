<?php
use Models\Model;

class CodeModel extends Model
{
    public function compareDate($validForHours, $startDate)
    {
        $format = 'Y/m/d H';
        $start  = \DateTime::createFromFormat($format, $startDate);
        $now    = \DateTime::createFromFormat($format, date($format));
        $end    = clone $start;
        $end->add(new \DateInterval('PT' . $validForHours . 'H'));
        if ($now > $end || $now < $start)
            return false;
        else
            return true;
    }

    public function loadParams()
    {
        $data = [];
        if ($file = fopen("settings/config.txt", "r")) {
            while (!feof($file)) {
                $line   = fgets($file);
                $length = strlen($line);
                if (!strpos($line, '#') && $length)
                    $data[] = $this->parseLine($line);
            }
            fclose($file);
        }
        return $data;
    }

    private function parseLine($line)
    {
        $data = [];
        $segments = explode('-', $line);
        foreach ($segments as $segment) {
            $parts      = explode(':', $segment);
            $key        = trim($parts[0]);
            $value      = trim($parts[1]);
            $value      = str_replace('"','',$this->parseParts($value));
            $data[$key] = $value;
        }
        return $data;
    }

    private function parseParts($part)
    {
        preg_match('/(".*?")/', $part, $matches);
        return $matches[0];
    }

    public function findCouponKey($data, $code)
    {
        return array_search($code, array_column($data, 'Code'));
    }

    public function calculatePrice($type, $value, $summ, $min) {
        $summ  = intval($summ);
        $value = intval($value);
        $res   = $summ;
        $msg   = '';
        if ($summ > $min) {
            if ($type == 'Percent') {
                $summ -= $summ * $value / 100;
            }
            if ($type == 'Amount') {
                $summ -= $value;
            }
            if ($summ < 0) {
                $summ = $res;
                $msg  = 'The order amount is too small to apply the discount.';
            }
            $res = $summ;
        } else {
            $msg = 'Minimal order amount for discount is $' . $min . ' CAD';
        }
        $response = ['summ' => $res, 'msg' => $msg];
        return $response;
    }
}