<?php

namespace App\Services;

use App\Models\RoleModel;
use Illuminate\Support\Carbon;
use App\Models\User;


class CustomConverterService
{
    static public function convertRole ($role) {
        if ($role) {
            $roles = explode(";", $role);

            $output = '';

            $len = count($roles);
            $i = 0;

            foreach ($roles as $e) {
                $output = $output . trim(RoleModel::find($e)->role);

                if ($i != $len - 1) {
                    $output = $output . ', ';
                }

                $i++;
            }

            return $output;
        }

        else {
            return "Not Defined Yet";
        }
    }

    static public function convertTime ($time) {
        $carbonObject = Carbon::createFromFormat('Y-m-d H:i:s', $time);

        return $carbonObject->format('D, d M Y');
    }

    static public function getLastLogin ($time) : string {
        $carbonObject = Carbon::createFromFormat('Y-m-d H:i:s', $time);

        $diffInMinutes = $carbonObject->diffInMinutes(Carbon::now());
        $diffInHours = $carbonObject->diffInHours(Carbon::now());
        $diffInDays = $carbonObject->diffInDays(Carbon::now());

        if ($diffInMinutes < 60) {
            return "$diffInMinutes mnts ago";
        } elseif ($diffInHours < 24) {
            $diffInMinutes = $diffInMinutes % 60;
            return "$diffInHours hrs, $diffInMinutes mnts ago";
        } else {
            $diffInHours = $diffInHours % 24;
            return "$diffInDays days, $diffInHours mnts ago";
        }
    }

    static public function convertStatus($status) {
        if ($status !== null) {
            if ($status == true) {
                return "Active";
            }

            else {
                return "Inactive";
            }
        }

        return "Inactive";
    }

    /**
     * Ini adalah fungsi yang tidak memiliki parameter yang berfungsi sebagai alat untuk
     * mengecek apakah user yang sedang login adalah seorang admin atau tidak.
     */
    static public function isAdmin () {
        $roles = explode(";", auth()->user()->role);

        foreach ($roles as $e) {
            if (RoleModel::find($e)->role == "Admin") {
                return true;
            }
        }

        return false;

        // dump(RoleModel::find(auth()->user()->role)->role == "Admin");
        // sleep(10);
    }


}
