/**
 * HomeLocationDialog
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

import React from 'react';
import ReactDOM from 'react-dom';
import Util from 'global/Util';
import HomeStore from 'stores/HomeStore';
import * as HomeLocationDialogActions from 'actions/HomeLocationDialogActions';

export default class HomeLocationDialog extends React.Component
{
    constructor()
    {
        super();
        this.state = this.getStateFromStores();
    }

    componentDidMount()
    {
        HomeStore.on('home_search_location_update', () => {
            this.setState(this.getStateFromStores());
        });
    }

    updateAddress(e)
    {
        let address = Object.assign({}, this.state.address);
        address[e.target.name] = e.target.value;

        HomeLocationDialogActions.updateAddress(address);
    }

    getStateFromStores()
    {
        return {
            search_option: HomeStore.getSearchLocationOption(),
            address: HomeStore.getHomeSearchAddress()
        };
    }

    renderCurrentLocationDialog()
    {
        return(
            <p>
                Roswell, GA
            </p>
        );
    }

    renderOtherLocationDialog()
    {
        // Address form
        let address_form = (<div>
            <div className="form-group">
                <label className="hidden-sm-up">Address Line</label>
                <input value={this.state.address.address_line_1} onChange={this.updateAddress.bind(this)} type="text" name="address_line_1" className="form-control" placeholder="Address Line"/>
            </div>
            <div className="form-inline">
                <label className="hidden-sm-up">City</label>
                <input value={this.state.address.city} onChange={this.updateAddress.bind(this)} type="text" className="form-control mb-2 mr-sm-2 mb-sm-0" name="city" placeholder="City"/>

                <label className="hidden-sm-up">State</label>
                <input value={this.state.address.state} onChange={this.updateAddress.bind(this)} type="text" className="form-control mb-2 mr-sm-2 mb-sm-0" name="state" placeholder="State"/>
            </div>
        </div>);

        let address = (<div>
            {this.state.address.address_line_1}<br/>
            {this.state.address.city}, {this.state.address.state}
        </div>);

        return(this.props.is_maximized ? address_form : address);
    }

    render()
    {
        return(
            <div>
                <h4>{this.state.search_option === 'current_location' ? 'Current Location' : 'Other Location'}</h4>
                {this.state.search_option === 'current_location' ? this.renderCurrentLocationDialog() : this.renderOtherLocationDialog()}
            </div>
        );
    }

    static initialize()
    {
        let elements = document.getElementsByClassName('home-location-dialog');
        for(let x = 0; x < elements.length; x++)
        {
            let element = elements[x];
            ReactDOM.render(<HomeLocationDialog/>, element);
        }
    }
}
