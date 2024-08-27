<?php
namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ResultQuery extends Builder
{

    /**
     * @return \App\Queries\ResultQuery
     */
    public function filter(Request $request): self
    {

        if($request->has('dateStart') && $request->has('dateEnd'))
        {
            $fromDate = $request->get('dateStart');
            $toDate = $request->get('dateEnd');

            $this->whereDate('date_parse', '>=', $fromDate)->whereDate('date_parse', '<=', $toDate);
        }

        if($request->has('countryStart'))
        {
            $countryStart = $request->get('countryStart');

            $this->whereHas('onloading', function ($query) use ($countryStart) {
                $query->whereHas('point', function ($query) use ($countryStart) {
                    $query->where('countryCode', $countryStart);
                });
            });

        }

        if($request->has('countryEnd'))
        {
            $countryEnd = $request->get('countryEnd');

            $this->whereHas('offloading', function ($query) use ($countryEnd) {
                $query->whereHas('point', function ($query) use ($countryEnd) {
                    $query->where('countryCode', $countryEnd);
                });
            });

        }

        return $this;

    }

}
