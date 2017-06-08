/**
 * HomePageSearchActions
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

import Dispatcher from 'frontend/Dispatcher';
import ActionTypes from 'frontend/ActionTypes';

export function updateAddress(address)
{
    Dispatcher.dispatch({
        type: ActionTypes.UPDATE_HOME_SEARCH_ADDRESS,
        data: {address}
    });
}