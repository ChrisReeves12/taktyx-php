/**
 * HomePageSearchActions
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

import Dispatcher from 'frontend/Dispatcher';
import ActionTypes from 'frontend/ActionTypes';

export function updateOption(option)
{
    Dispatcher.dispatch({
        type: ActionTypes.UPDATE_SEARCH_FORM_OPTION,
        data: {option}
    });
}

export function updateMinimizeToggle(is_maximized)
{
    Dispatcher.dispatch({
        type: ActionTypes.UPDATE_SEARCH_OPTION_MINIMIZE,
        data: {is_maximized}
    });
}