/**
 * HomeSearchForm
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

import React from 'react';
import ReactDOM from 'react-dom';
import HomeStore from 'stores/HomeStore';
import SearchResultLine from 'components/home_search_form/SearchResultLine';
import * as SendTaktPopupActions from 'actions/SendTaktPopupActions';
import * as HomePageSearchActions from 'actions/HomePageSearchActions';
import Util from 'global/Util';

export default class HomeSearchForm extends React.Component
{
    constructor()
    {
        super();
        this.state = this.getStateFromStores();
        this.input_timer = null;
    }

    componentDidMount()
    {
        HomeStore.on('change_search_form', () => {
            this.setState(this.getStateFromStores());
        });
    }

    getStateFromStores()
    {
        return {
            search_results: HomeStore.getAllSearchResults(),
            search_text: HomeStore.getSearchText(),
            is_loading: HomeStore.getIsLoading()
        };
    }

    addSearchResult(e)
    {
        HomePageSearchActions.addSearchResult('Flux seems pretty overengineered...');
    }

    getResultSearchResultLine(result)
    {
        return(<SearchResultLine result={result}/>);
    }

    fetchSearchResults()
    {
        HomePageSearchActions.fetchSearchResults(this.state.search_text);
    }

    updateInput(e)
    {
        if(this.input_timer !== null)
        {
            window.clearTimeout(this.input_timer);
        }

        this.input_timer = window.setTimeout(this.fetchSearchResults.bind(this), 400);
        HomePageSearchActions.updateInput(e.target.value);
    }

    doSelectAll(e)
    {
        e.preventDefault();
        HomePageSearchActions.doSelectAll();
    }

    doUnselectAll(e)
    {
        e.preventDefault();
        HomePageSearchActions.doUnselectAll();
    }

    getShouldShowShowSelectAll()
    {
        // If all results are selected, show select all button
        return(this.state.search_results.filter(sr => sr.selected).length < this.state.search_results.length);
    }

    getShouldShowSearchButtons()
    {
        // If there are no results, hide the search bar
        return(this.state.search_results.length > 0);
    }

    getShouldShowMassTaktSendButton()
    {
        return(this.state.search_results.filter(sr => sr.selected).length > 0);
    }

    doShowTaktPopup(e)
    {
        window.console.log(this.state);
        e.preventDefault();
        let data = {};
        data.recipients = this.state.search_results.filter(sr => sr.selected);
        SendTaktPopupActions.showTaktPopup(data);
    }

    render()
    {
        return(
            <div className="home-search-form-container">
                <div className="search-form">
                    <div className='input-group'>
                        <input onChange={this.updateInput.bind(this)}
                               type="text" className='form-control input-lg'
                               placeholder="Search"/>

                        <span className='input-group-btn'>
                            <button onClick={this.addSearchResult.bind(this)} className='btn btn-info btn-lg' type="button">
                                <i className='fa fa-search'/>
                            </button>
                        </span>
                    </div>
                </div>
                {this.getShouldShowSearchButtons() &&
                <div className="search-result-buttons">
                    {this.getShouldShowShowSelectAll() &&
                    <button onClick={this.doSelectAll.bind(this)} className="select-all-button">
                        <i className="fa fa-mouse-pointer"/> Select All
                    </button>}
                    {this.getShouldShowMassTaktSendButton() &&
                    <button onClick={this.doShowTaktPopup.bind(this)} className="send-takt-button">
                        <img src={Util.staticAssets('img/layout/taktyx_icon1.png')}/> Send Takt(s)
                    </button>}
                    <button onClick={this.doUnselectAll.bind(this)} className="unselect-all">
                        <i className="fa  fa-square-o "/> Unselect All
                    </button>
                </div>}
            </div>
        );
    }

    static initialize()
    {
        let elements = document.getElementsByClassName('home-search-form');
        for(let x = 0; x < elements.length; x++)
        {
            let element = elements[x];
            ReactDOM.render(<HomeSearchForm/>, element);
        }
    }
}
