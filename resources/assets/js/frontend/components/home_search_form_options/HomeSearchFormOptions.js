/**
 * HomeSearchFormOptions
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

import React from 'react';
import ReactDOM from 'react-dom';
import * as HomeSearchFormOptionsActions from 'actions/HomeSearchFormOptionsActions';
import HomeStore from 'stores/HomeStore';
import HomeLocationDialog from 'components/home_location_dialog/HomeLocationDialog';
import Util from 'global/Util';
import Constants from 'global/Constants';

export default class HomeSearchFormOptions extends React.Component
{
    constructor()
    {
        super();
        this.state = this.getStateFromStores();
    }

    getStateFromStores()
    {
        return {
            search_location_option: HomeStore.getSearchLocationOption(),
            search_location_is_maximized: HomeStore.getSearchLocationIsMaximized()
        };
    }

    componentDidMount()
    {
        HomeStore.on('home_search_location_update', () => {
            this.setState(this.getStateFromStores());
        });

        HomeStore.on('change_search_form', () => {
            if(HomeStore.getAllSearchResults().length > 0 && $(window).width() < Constants.MOBILE_WIDTH_MAX)
            {
                this.setState({search_location_is_maximized: false});
            }
        });
    }

    updateChoice(e)
    {
        HomeSearchFormOptionsActions.updateOption($(e.target).parent().data('value'));
    }

    renderOptionsSection()
    {
        if(this.state.search_location_is_maximized)
        {
            return(
                <div className="options-section">
                    <div className="option">
                        <div data-value="current_location" onClick={this.updateChoice.bind(this)} className="option-radio">
                            <img src={Util.staticAssets('img/layout/' +
                                (this.state.search_location_option === 'current_location' ? 'checked' : 'unchecked') + "_radio.png")}/>
                        </div>
                        <label>Use current location</label>
                    </div>
                    <div className="option">
                        <div data-value="other_location" onClick={this.updateChoice.bind(this)} className="option-radio">
                            <img src={Util.staticAssets('img/layout/' +
                                (this.state.search_location_option === 'other_location' ? 'checked' : 'unchecked') + "_radio.png")}/>
                        </div>
                        <label>Use another location</label>
                    </div>
                </div>
            );
        }
    }

    renderMinMaxButton()
    {
        let minimize_button_sign = this.state.search_location_is_maximized ? 'minus' : 'plus';

        // Show for mobile only
        if($(window).width() < Constants.MOBILE_WIDTH_MAX)
        {
            return(
                <div onClick={this.doMinimizeToggle.bind(this)} className="minimize-button">
                    <i className={`fa fa-${minimize_button_sign}`}/>
                </div>
            );
        }
    }

    doMinimizeToggle()
    {
        HomeSearchFormOptionsActions.updateMinimizeToggle(this.state.search_location_is_maximized);
    }

    render()
    {
        return(
            <div className="search-form-options-section">
                {this.renderMinMaxButton()}
                {this.renderOptionsSection()}
                <HomeLocationDialog is_maximized={this.state.search_location_is_maximized}/>
            </div>
        );
    }

    static initialize()
    {
        let elements = document.getElementsByClassName('home-search-form-options');
        for(let x = 0; x < elements.length; x++)
        {
            let element = elements[x];
            ReactDOM.render(<HomeSearchFormOptions/>, element);
        }
    }
}
