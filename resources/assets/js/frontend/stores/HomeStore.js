/**
 * HomeStore
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

import { EventEmitter } from 'events';
import Dispatcher from 'frontend/Dispatcher';
import ActionTypes from 'frontend/ActionTypes';

class HomeStore extends EventEmitter
{
    constructor()
    {
        super();
        this.search_results = [];
        this.search_text = '';
        this.search_location_option = 'current_location';
        this.search_location_is_maximized = true;
        this.is_loading = false;
        this.takt_recipient_data = {};
        this.home_search_address = {
            address_line_1: '',
            city: '',
            state: ''
        };
    }

    getAllSearchResults()
    {
        return this.search_results;
    }

    getSearchLocationOption()
    {
        return this.search_location_option;
    }

    getSearchLocationIsMaximized()
    {
        return this.search_location_is_maximized;
    }

    getTaktRecipientData()
    {
        return this.takt_recipient_data;
    }

    getHomeSearchAddress()
    {
        return this.home_search_address;
    }

    getSearchText()
    {
        return this.search_text;
    }

    getIsLoading()
    {
        return this.is_loading;
    }

    // Handle incoming actions
    handleActions(action)
    {
        switch(action.type)
        {
            case ActionTypes.ADD_SEARCH_RESULT:
            {
                this.search_results.push(action.data.result);
                this.emit('change_search_form');
                break;
            }

            case ActionTypes.UPDATE_SEARCH_INPUT:
            {
                this.search_text = action.data.text;
                this.emit('change_search_form');
                break;
            }

            case ActionTypes.START_FETCHING_SEARCH_RESULTS:
            {
                this.is_loading = true;
                this.emit('change_search_form');
                break;
            }

            case ActionTypes.FETCH_SEARCH_RESULTS:
            {
                this.is_loading = false;
                this.search_results = action.data.search_results;
                this.emit('change_search_form');
                break;
            }

            case ActionTypes.UPDATE_SEARCH_FORM_OPTION:
            {
                this.search_location_option = action.data.option;
                this.search_results = [];
                this.emit('change_search_form');
                this.emit('home_search_location_update');
                break;
            }

            case ActionTypes.UPDATE_SEARCH_RESULT_SELECT:
            {
                this.search_results[action.data.idx].selected = !this.search_results[action.data.idx].selected;
                this.emit('change_search_form');
                break;
            }

            case ActionTypes.SELECT_ALL_SEARCH_RESULTS:
            {
                for(let x = 0; x < this.search_results.length; x++)
                {
                    this.search_results[x].selected = true;
                }
                this.emit('change_search_form');
                break;
            }

            case ActionTypes.UNSELECT_ALL_SEARCH_RESULTS:
            {
                for(let x = 0; x < this.search_results.length; x++)
                {
                    this.search_results[x].selected = false;
                }
                this.emit('change_search_form');
                break;
            }

            case ActionTypes.SHOW_TAKT_POPUP:
            {
                this.takt_recipient_data = action.data;
                this.takt_recipient_data.visible = true;
                this.emit('change_takt_popup');
                break;
            }

            case ActionTypes.CLOSE_TAKT_POPUP:
            {
                this.takt_recipient_data.visible = false;
                this.emit('change_takt_popup');
                break;
            }

            case ActionTypes.UPDATE_TAKT_MESSAGE_POPUP_INPUT:
            {
                this.takt_recipient_data.takt_message = action.data.message;
                this.emit('change_takt_popup');
                break;
            }

            case ActionTypes.UPDATE_SEARCH_OPTION_MINIMIZE:
            {
                this.search_location_is_maximized = !action.data.is_maximized;
                this.emit('home_search_location_update');
                break;
            }

            case ActionTypes.UPDATE_HOME_SEARCH_ADDRESS:
            {
                this.home_search_address = action.data.address;
                this.emit('home_search_location_update');
                break;
            }
        }
    }
}

const homeSearchStore = new HomeStore();
Dispatcher.register(homeSearchStore.handleActions.bind(homeSearchStore));

export default homeSearchStore;
