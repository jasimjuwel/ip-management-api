<?php

namespace App\Repositories;

use App\Models\IpTable;
use Illuminate\Support\Facades\Log;

class IpTableRepository
{
    /**
     * Store IP
     * @param array $data
     * @return  mixed
     */
    public function ipStore(array $data): mixed
    {
        try {
            $ipData = IpTable::create([
                'ip_address' => $data['ip_address'],
                'ip_label' => $data['ip_label'],
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);

            return $ipData;
        } catch (\Exception $e) {
            Log::error('IpTableRepository@ipStore response message: ' . $e->getMessage(), $e->getTrace());

            return false;
        }
    }

    /**
     * get ip list
     * @param $perPage
     */
    public function getIpList(int $perPage = 10)
    {
        try {
            return IpTable::orderBy('id', 'desc')->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('IpTableRepository@getIpList response message: ' . $e->getMessage(), $e->getTrace());

            return false;
        }
    }

    /**
     * get IP Details
     * @param $id
     */
    public function getIpDetails(int $id)
    {
        try {
            $ip = IpTable::findOrFail($id);

            return $ip;
        } catch (\Exception $e) {
            Log::error('IpTableRepository@getIpDetails response message: ' . $e->getMessage(), $e->getTrace());

            return false;
        }
    }

    /**
     * Update IP
     */
    public function updateIp(array $data, int $id)
    {
        try {
            $ip = IpTable::findOrFail($id);
            $ip->ip_label = $data['ip_label'];
            $ip->updated_by = auth()->user()->id;

            return $ip;
        } catch (\Exception $e) {
            Log::error('IpTableRepository@updateIp response message: ' . $e->getMessage(), $e->getTrace());

            return false;
        }
    }
}
