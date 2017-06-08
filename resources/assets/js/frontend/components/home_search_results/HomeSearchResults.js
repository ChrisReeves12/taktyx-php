/**
 * HomeSearchResults
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

import React from 'react';
import ReactDOM from 'react-dom';
import HomeSearchResult from './HomeSearchResult';
import HomeStore from 'stores/HomeStore';
import Util from 'global/Util';

export default class HomeSearchResults extends React.Component
{
    constructor()
    {
        super();
        this.state = this.getStateFromStores();
    }

    componentDidMount()
    {
        HomeStore.on('change_search_form', () => {
            this.setState(this.getStateFromStores());
        })
    }

    getStateFromStores()
    {
        return {
            search_results: HomeStore.getAllSearchResults(),
            is_loading: HomeStore.getIsLoading()
        };
    }

    getSearchResultLines()
    {
        let idx = -1;
        return this.state.search_results.map(sr => {
            idx++;
            return(<HomeSearchResult key={sr.id} idx={idx} data={sr}/>);
        });
    }

    render()
    {
        return(
            <div className="home-search-results-container">
                {!this.state.is_loading && <div>
                    {this.getSearchResultLines()}
                </div>}
                {this.state.is_loading && <div className="loader-container">
                    <img src={Util.staticAssets('img/layout/loader1.gif')}/>
                </div>}
            </div>
        );
    }

    static initialize()
    {
        let elements = document.getElementsByClassName('home-search-results');
        for(let x = 0; x < elements.length; x++)
        {
            let element = elements[x];
            ReactDOM.render(<HomeSearchResults/>, element);
        }
    }
}
