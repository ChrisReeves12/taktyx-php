/**
 * HomePageSearchActions
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

import Dispatcher from 'frontend/Dispatcher';
import ActionTypes from 'frontend/ActionTypes';

export function updateSearchResultSelect(idx)
{
    Dispatcher.dispatch({
        type: ActionTypes.UPDATE_SEARCH_RESULT_SELECT,
        data: {idx}
    });
}