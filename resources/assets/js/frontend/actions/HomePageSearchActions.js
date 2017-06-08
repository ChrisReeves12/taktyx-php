/**
 * HomePageSearchActions
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

import Dispatcher from 'frontend/Dispatcher';
import ActionTypes from 'frontend/ActionTypes';

export function addSearchResult(result)
{
    Dispatcher.dispatch({
        type: ActionTypes.ADD_SEARCH_RESULT,
        data: {result}
    });
}

export function updateInput(text)
{
    Dispatcher.dispatch({
        type: ActionTypes.UPDATE_SEARCH_INPUT,
        data: {text}
    });
}

export function doSelectAll()
{
    Dispatcher.dispatch({
        type: ActionTypes.SELECT_ALL_SEARCH_RESULTS
    });
}

export function doUnselectAll()
{
    Dispatcher.dispatch({
        type: ActionTypes.UNSELECT_ALL_SEARCH_RESULTS
    });
}

export function fetchSearchResults(search_text)
{
    // Imitate server call
    let search_results = [];

    if(search_text === '')
    {
        Dispatcher.dispatch({
            type: ActionTypes.FETCH_SEARCH_RESULTS,
            data: {search_text, search_results}
        });
    }
    else
    {
        Dispatcher.dispatch({type: ActionTypes.START_FETCHING_SEARCH_RESULTS});
        window.setTimeout(() =>
        {
            if(search_text.length < 5)
            {
                search_results = [
                    {
                        id: 1,
                        label: 'Search Result 1',
                        img_url: 'http://lorempixel.com/100/100',
                        href: 'http://google.com',
                        desc: 'Description for search 1',
                        selected: false
                    },
                    {
                        id: 2,
                        label: 'Search Result 2',
                        img_url: 'http://lorempixel.com/101/101',
                        href: 'http://facebook.com',
                        desc: 'Description for search 2',
                        selected: false
                    },
                    {
                        id: 3,
                        label: 'Search Result 3',
                        img_url: 'http://lorempixel.com/102/102',
                        href: 'http://yahoo.com',
                        desc: 'Description for search 3',
                        selected: false
                    }
                ];
            }
            else
            {
                search_results = [
                    {
                        id: 4,
                        label: 'Search Result 4',
                        img_url: 'http://lorempixel.com/103/103',
                        href: 'http://twitter.com',
                        desc: 'Description for search 4',
                        selected: false
                    },
                    {
                        id: 5,
                        label: 'Search Result 5',
                        img_url: 'http://lorempixel.com/104/104',
                        href: 'http://google.com',
                        desc: 'Description for search 5',
                        selected: false
                    },
                    {
                        id: 6,
                        label: 'Search Result 6',
                        img_url: 'http://lorempixel.com/105/105',
                        href: 'http://facebook.com',
                        desc: 'Description for search 6',
                        selected: false
                    },
                    {
                        id: 7,
                        label: 'Search Result 7',
                        img_url: 'http://lorempixel.com/106/106',
                        href: 'http://yahoo.com',
                        desc: 'Description for search 7',
                        selected: false
                    },
                    {
                        id: 8,
                        label: 'Search Result 8',
                        img_url: 'http://lorempixel.com/107/107',
                        href: 'http://twitter.com',
                        desc: 'Description for search 8',
                        selected: false
                    },
                    {
                        id: 8,
                        label: 'Search Result 8',
                        img_url: 'http://lorempixel.com/107/107',
                        href: 'http://twitter.com',
                        desc: 'Description for search 8',
                        selected: false
                    },
                    {
                        id: 9,
                        label: 'Search Result 9',
                        img_url: 'http://lorempixel.com/107/107',
                        href: 'http://twitter.com',
                        desc: 'Description for search 8',
                        selected: false
                    },
                    {
                        id: 10,
                        label: 'Search Result 10',
                        img_url: 'http://lorempixel.com/107/107',
                        href: 'http://twitter.com',
                        desc: 'Description for search 8',
                        selected: false
                    },
                ];
            }

            Dispatcher.dispatch({
                type: ActionTypes.FETCH_SEARCH_RESULTS,
                data: {search_text, search_results}
            });
        }, 500);
    }
}
