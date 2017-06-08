<?php
/**
 * The ReplyRepository class definition.
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 * @author Craig Reeves <reevescd1@gmail.com>
 **/

namespace App\Services\Repositories;

use App\Contracts\IRepository;
use App\Reply;
use Validator;

/**
 * Class ReplyRepository
 * @package App\Services\Repositories
 */
class ReplyRepository extends BaseRepositoryImpl implements IRepository
{
    private $takt_repository;

	/**
	 * ReplyRepository constructor.
	 * @param TaktRepository $takt_repository
	 */
    public function __construct(TaktRepository $takt_repository)
    {
        $this->takt_repository = $takt_repository;
        $this->setClass(Reply::class);
    }

	/**
	 * @param \Illuminate\Http\Request $request
	 * @param null $controller
	 * @param null $takt_id
	 * @return Reply
	 */
    public function store($request, $controller = null, $takt_id = null)
    {
        // validations
        $this->_validate_class();
        $this->_validate_record($request->all());

        // create reply
        $reply = new Reply([
            'content' => $request->reply_content
        ]);

        // save the reply
        $this->save($reply);

        return $reply;
    }
}