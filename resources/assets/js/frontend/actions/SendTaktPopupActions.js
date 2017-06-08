/**
 * HomePageSearchActions
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

import Dispatcher from 'frontend/Dispatcher';
import ActionTypes from 'frontend/ActionTypes';

export function showTaktPopup(data)
{
    // Set up dark screen and scroll lock
    $('body').append('<div class="dark-screen"></div>')
        .addClass('stop-scrolling');

    Dispatcher.dispatch({
        type: ActionTypes.SHOW_TAKT_POPUP,
        data: data
    });
}

export function closeTaktMessagePopup()
{
    // Remove dark screen and scroll lock
    let body_element = $('body');
    body_element.find('.dark-screen').remove();
    body_element.removeClass('stop-scrolling');

    Dispatcher.dispatch({
        type: ActionTypes.CLOSE_TAKT_POPUP
    });
}

export function updateTaktMessageInput(message)
{
    Dispatcher.dispatch({
        type: ActionTypes.UPDATE_TAKT_MESSAGE_POPUP_INPUT,
        data: {message}
    });
}