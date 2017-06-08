<?php
/**
 * The ImageRepository class definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Repositories;

use App\Contracts\IRepository;
use App\Image;
use Illuminate\Http\Request;

/**
 * Class ImageRepository
 * @package App\Services\Repositories
 */
class ImageRepository extends BaseRepositoryImpl implements IRepository
{
	/**
	 * ImageRepository constructor.
	 */
	public function __construct()
	{
		$this->setClass(Image::class);
	}

	/**
	 * Save image
	 * @param Request $request
	 * @param string $foreign_key
	 * @return Image
	 */
	public function store($request, $foreign_key = null)
	{
		// handle validation
		$this->_validate_class();
		$this->_validate_record($request->all());

		$file = $request->file('image');
		$image_name = time() . $file->getClientOriginalName();
		$file->move('img', $image_name);
		$image = new Image(['path' => $image_name]);
		$this->save($image);

		return $image;
	}

	/**
	 * Update image
	 * @param Image $image
	 * @param Request $request
	 * @param string $foreign_key
	 * @return Image
	 */
	public function update($image, $request, $foreign_key = null)
	{
		// handle validation
		$this->_validate_class();
		$this->_validate_record($request->all());

		// handle uploading file
		$file = $request->file('image');
		$image_name = time() . $file->getClientOriginalName();
		$file->move('img', $image_name);
		$image->path = $image_name;
		$this->save($image);

		// return the image
		return $image;
	}

	/**
	 * Get generic profile image
	 * @param $type
	 * @return string
	 */
	public function generic($type)
	{
		$ret_val = '';

		switch($type)
		{
			case 'customer':
				$ret_val = 'genericprofile.jpg';
			break;

			case 'service':
				$ret_val = 'genericservice.png';
			break;

			case 'employee':
				$ret_val = 'genericprofile.jpg';
			break;
		}

		return $ret_val;
	}
}