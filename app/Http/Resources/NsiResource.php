<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NsiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          "id"=> $this->id,
          "fullname"=> $this->fullname,
          "old_name"=> $this->old_name,
          "start_date"=> $this->start_date,
          "accept_date"=> $this->accept_date,
          "accept_number"=> $this->accept_number,
          "accept_odm"=> $this->accept_odm,
          "odm_number"=> $this->odm_number,
          "npa_type"=> $this->npa_type,
          "city"=> $this->city,
          "year"=> $this->year,
          "pages"=> $this->pages,
          "nsiDate"=> $this->nsiDate,
          "nsiNumber"=> $this->nsiNumber,
          "nsiEdit"=> $this->nsiEdit,
          "nsiName"=> $this->nsiName,
          "nsiApproveName"=> $this->nsiApproveName,
          "nsiProtocolDate"=> $this->nsiProtocolDate,
          "nsiCode"=> $this->nsiCode,
          "nsiPeriod"=> $this->nsiPeriod,
          "nsiBasis"=> $this->nsiBasis,
          "nsiAuthors"=> $this->nsiAuthors,
          "nsiAuthors"=> $this->nsiAuthors,
          "nsiEditor"=> $this->nsiEditor,
          "nsiCity"=> $this->nsiCity,
          "nsiYear"=> $this->nsiYear,
          "nsiPages"=> $this->nsiPages,
          "nsiProtocolNumber"=> $this->nsiProtocolNumber,
          "nsiLink"=> $this->nsiLink,
          "nsiFullName"=> $this->nsiFullName,
          "nsiMinistry"=> $this->nsiMinistry,
          "type_id" => $this->type_id,
          "typeName" => $this->type,

        ];
    }
}
