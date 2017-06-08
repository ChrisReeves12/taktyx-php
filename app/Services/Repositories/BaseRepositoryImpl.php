<?php
/**
 * The BaseRepositoryImpl class definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Repositories;

use App\Contracts\IRepository;
use App\Contracts\ISQLStorable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Validator;

/**
 * Class BaseRepositoryImpl
 * @package App\Services\Repositories
 */
class BaseRepositoryImpl implements IRepository
{
	protected $class;

	/**
	 * Store model
	 * @param Request $request
	 * @param string $foreign_key
	 * @return Model
	 */
	public function store($request, $foreign_key = null)
	{
		$this->_validate_class();
		$this->_validate_record($request->all());

		// Create the model
		$model_data = $request->all();
		unset($model_data['_token']);

		return $this->class::create($model_data);
	}

	/**
	 * Update model
	 * @param Model $updateable
	 * @param Request $request
	 * @param string $foreign_key
	 * @return Model
	 * @throws \Exception
	 */
	public function update($updateable, $request, $foreign_key = null)
	{
		$this->_validate_class();

		if(!($updateable instanceof Model))
		{
			throw new \Exception('To update a model, it must extend ' . Model::class);
		}

		$this->_validate_record($request->all());

		// Create the model
		$model_data = $request->all();
		unset($model_data['_token']);
		$updateable->update($model_data);

		return $updateable;
	}

	/**
	 * @param Model $saveable
	 * @throws \Exception
	 */
	public function save($saveable)
	{
		$this->_validate_class();

		if(!($saveable instanceof Model))
		{
			throw new \Exception('To save a model, it must extend ' . Model::class);
		}

		$saveable->save();
	}

	/**
	 * @param int $id
	 */
	public function find($id)
	{
		$this->_validate_class();
		return $this->class::find($id);
	}

	/**
	 * @param string $column
	 * @param mixed $value
	 */
	public function find_by($column, $value)
	{
		$this->_validate_class();
		return $this->class::where($column, $value)->first();
	}

	public function all($sort_by = 'created_at')
	{
		$this->_validate_class();
		return $this->class::orderBy($sort_by)->get();
	}

	/**
	 * @param Model $model
	 * @throws \Exception
	 */
	public function delete($model)
	{
		$this->_validate_class();

		if(!($model instanceof Model))
		{
			throw new \Exception('To delete a model, it must extend ' . Model::class);
		}

		$model->delete();
	}

	/**
	 * @return string
	 */
	public function getClass()
	{
		return $this->class;
	}

	/**
	 * @param string $class
	 */
	public function setClass($class)
	{
		$this->class = $class;
	}

	/**
	 * Validates the record
	 * @param array $data
	 */
	protected function _validate_record(array $data)
	{
		unset($data['_token']);
		$record = new $this->class();

		if(!empty($validation_rules = $record->getValidationRules()))
		{
			Validator::make($data, $validation_rules)->validate();
		}
	}

	/**
	 * Check if class implements and extends the corrected classes and interfaces
	 * @return bool
	 * @throws \Exception
	 */
	protected function _validate_class()
	{
		if(!class_exists($this->class))
			throw new \Exception('The class ' . $this->class . ' does not exist to be used in repository.');

		if(!in_array(ISQLStorable::class, class_implements($this->class)))
			throw new \Exception('The class ' . $this->class . ' must implement the ISQLStorable interface.');

		if(!in_array(Model::class, class_parents($this->class)))
			throw new \Exception('The class ' . $this->class . ' must be a Model.');

		return true;
	}
}