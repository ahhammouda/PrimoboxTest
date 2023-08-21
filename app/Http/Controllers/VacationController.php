<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class VacationController extends Controller
{
    // Response codes and messages
    private $codes = [
        '200' => 'Request was successful',
        '400' => 'Request was malformed',
        '404' => 'Token not valid',
        '405' => 'Vacations should not start on weekends',
        '500' => 'Internal Server error'
    ];

    // API endpoint for splitting vacation days
    public function splitVacation(Request $request)
    {
        try {
            // Implement a robust security measure by adding a hash code to prevent vulnerabilities such as man-in-the-middle attacks.
            $response = $this->checkToken($request);
            if ($response != 'OK') {
                return $response;
            }

            try {
                // Validate the incoming request data
                $this->validateRequest($request);
            } catch (\Throwable $th) {
                return $this->response(400);
            }

            // Parse start and end date from the request
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            // Check if start date falls on a weekend
            if ($startDate->isWeekend()) {
                return $this->response(405);
            }

            // Calculate and split vacation days
            $result = $this->splitAndCalculate($startDate, $endDate);

            // Return a successful response with the calculated result
            return $this->response(200, $result);
        } catch (\Throwable $th) {
            // Handle unexpected exceptions and return an internal server error response
            return $this->response(500); // Internal Server Error
        }
    }

    // Validate the request data
    private function validateRequest(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
    }

    // Create a standardized response array
    private function response($code, $data = null)
    {
        return [
            'code' => $code,
            'message' => $this->codes[$code],
            'data' => $data,
        ];
    }

    // Check the authenticity of the request using a security token
    function checkToken($request) {
        $token = request('token');
        $secretcode = "PrimoBoxToken";
        if ($token != $secretcode) {
            return $this->response(404);
        } else {
            return 'OK';
        }
    }

    // Count the number of weekend days between two dates
    private function countWeekendDays($start, $end)
    {
        $weekendDays = 0;

        while ($start <= $end) {
            if ($start->isWeekend()) {
                $weekendDays++;
            }
            $start->addDay();
        }

        return $weekendDays;
    }

    // Split and calculate vacation days for multiple months
    private function splitAndCalculate($startDate, $endDate)
    {
        if ($startDate->isSameMonth($endDate)) {
            $result = $this->monthlyLeave($startDate, $endDate);
            return $result;
        }
        $periods = $this->splitPeriodByMonth($startDate, $endDate);
        $result = [];

        foreach ($periods as $period) {
            $result[] = $this->monthlyLeave($period->start, $period->end);
        }

        return $result;
    }

    // Split a date range into periods by month
    private function splitPeriodByMonth($startDate, $endDate)
    {
        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');
        $period = \Carbon\CarbonPeriod::create($startDate, '1 month', $endDate);

        $intervals = [];

        foreach ($period as $date) {
            $intervals[] = (object)[
                "start" => $date->firstOfMonth()->max($startDate)->toDateString(),
                "end" => $date->lastOfMonth()->min($endDate)->toDateString()
            ];
        }

        return $intervals;
    }

    // Calculate vacation days for a single month
    private function monthlyLeave($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $monthYear = $startDate->format('F Y');
        $numberOfDays = $startDate->diffInDays($endDate) + 1;
        $weekendDays = $this->countWeekendDays($startDate, $endDate);
        $vacationDays = $numberOfDays - $weekendDays;

        return [$vacationDays, $monthYear];
    }
}
