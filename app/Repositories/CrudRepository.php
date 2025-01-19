<?php

namespace App\Repositories;

use App\Interfaces\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Model;


class CrudRepository extends BaseRepository implements CrudRepositoryInterface
{
   public function index(Model $model, $paginated = false, $folder = null, $files = null, $where = [], $whereNot = [], $search = [], $active = null, $verify = null)
   {
      $query = $model->query();
      $query = $this->whereWhereNotSearch($query, $where, $whereNot, $search);
      $query = $this->active($query, $active);
      $query = $this->verified($query, $verify);

      return $paginated ? $query->paginate(env('PAGINATE')) : $query->get();
   }

   public function getById(Model $model, $id, $folder = null, $files = null, $where = [], $whereNot = [], $search = [], $active = null, $verify = null)
   {
      $query = $model->findOrFail($id);
      $query = $this->whereWhereNotSearch($query, $where, $whereNot, $search);
      $query = $this->active($query, $active);
      $query = $this->verified($query, $verify);

      return $query;
   }

   public function store(Model $model,  $data, $request,  $folder = null, $files = [], array $images = [], $modified_values = [], $hashing_values = [])
   {
      $data = $this->updateDatas($data,  $modified_values = null,  $hashing_values = null);
      $createdModel = $model->create($data);

      $this->storeImagesWithNames($request, $folder, $files, $createdModel->id,);
      return $createdModel;
   }
   public function update(Model $model, array $data, $id, $request = [],  $folder = null, $files = [], array  $modified_values = [],  $hashing_values = null,  array $where = [], array $whereNot = [], array $search = [], $active = null, $verify = null)
   {

      $data = $this->updateDatas($data,  $modified_values = null,  $hashing_values = null);
      $query = $model->whereId($id);
      $query = $this->whereWhereNotSearch($query, $where, $whereNot, $search);
      $query = $this->active($query, $active);
      $query = $this->verified($query, $verify);
      $data = $this->storeImagesWithNames($request, $folder, $files = [], $id);
      dd($data);
      return $query->update($data);
   }

   public function delete(Model $model, $folder,  $id = null, array $where = [], array $whereNot = [], array $search = [], $active = null, $verify = null)
   {
      try {
         $query = $model->query();
         if ($id != null) {
            $query = $query->findOrFail($id);
         }
         $query = $this->whereWhereNotSearch($query, $where, $whereNot, $search);
         $query = $this->active($query, $active);
         $query = $this->verified($query, $verify);

         $query->delete();
         $this->deleteFolderById($folder, $id);
      } catch (\Exception $e) {
         return response()->json(['error' => $e->getMessage()], 500);
      }
   }

   public function verify(Model $model, $id, $folder = null, $files = null, array $where = [], array $whereNot = [], array $search = [], $active = null, $verify = null)
   {
      $query = $model->whereId($id);
      $query = $this->whereWhereNotSearch($query, $where, $whereNot, $search);
      $query = $this->active($query, $active);
      $query = $this->verified($query, $verify);

      return $query->update(['verified' => 1]);
   }

   public function unverify(Model $model, $id, $folder = null, $files = null, array $where = [], array $whereNot = [], array $search = [], $active = null, $verify = null)
   {
      $query = $model->whereId($id);
      $query = $this->whereWhereNotSearch($query, $where, $whereNot, $search);
      $query = $this->active($query, $active);
      $query = $this->verified($query, $verify);

      return $query->update(['verified' => 0]);
   }

   public function groupBy(Model $model, $groupBy, $paginated = false, $folder = null, $files = null, array $where = [], array $whereNot = [], array $search = [], $active = null, $verify = null)
   {
      $query = $model->query();
      $query = $this->whereWhereNotSearch($query, $where, $whereNot, $search);
      $query = $this->active($query, $active);
      $query = $this->verified($query, $verify);
      return $query = $this->multipleGroupBy($paginated ? $query->paginate(env('PAGINATE')) : $query->get(), $groupBy);
   }
   public function getByDate(Model $model, $date, $column, $paginated = false, $folder = null, $files = null, array $where = [], array $whereNot = [], array $search = [], $active = null, $verify = null)
   {
      $query = $model->whereDate($column, $date);
      $query = $this->whereWhereNotSearch($query, $where, $whereNot, $search);
      $query = $this->active($query, $active);
      $query = $this->verified($query, $verify);

      return $paginated ? $query->paginate(env('PAGINATE')) : $query->get();
   }

   public function getBetweenDate(Model $model, $date, $column, $paginated = false, $folder = null, $files = null, array $where = [], array $whereNot = [], array $search = [], $active = null, $verify = null)
   {
      $query = $model->whereBetween($column, $date);
      $query = $this->whereWhereNotSearch($query, $where, $whereNot, $search);
      $query = $this->active($query, $active);
      $query = $this->verified($query, $verify);

      return $paginated ? $query->paginate(env('PAGINATE')) : $query->get();
   }

   public function getMoreThan(Model $model, $data, $column, $paginated = false, $folder = null, $files = null, array $where = [], array $whereNot = [], array $search = [], $active = null, $verify = null)
   {
      $query = $model->where($column, '>', $data);
      $query = $this->whereWhereNotSearch($query, $where, $whereNot, $search);
      $query = $this->active($query, $active);
      $query = $this->verified($query, $verify);

      return $paginated ? $query->paginate(env('PAGINATE')) : $query->get();
   }

   public function getLessThan(Model $model, $data, $column, $paginated = false, $folder = null, $files = null, array $where = [], array $whereNot = [], array $search = [], $active = null, $verify = null)
   {
      $query = $model->where($column, '<', $data);
      $query = $this->whereWhereNotSearch($query, $where, $whereNot, $search);
      $query = $this->active($query, $active);
      $query = $this->verified($query, $verify);

      return $paginated ? $query->paginate(env('PAGINATE')) : $query->get();
   }

   public function getMoreThanEqual(Model $model,  $data, $column, $paginated = false, $folder = null, $files = null, array $where = [], array $whereNot = [], array $search = [], $active = null, $verify = null)
   {
      $query = $model->where($column, '>=', $data);
      $query = $this->whereWhereNotSearch($query, $where, $whereNot, $search);
      $query = $this->active($query, $active);
      $query = $this->verified($query, $verify);

      return $paginated ? $query->paginate(env('PAGINATE')) : $query->get();
   }
}
