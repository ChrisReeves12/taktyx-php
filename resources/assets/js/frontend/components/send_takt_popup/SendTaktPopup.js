/**
 * SendTaktPopup
 *
 * @author Christopher Lee Reeves <ChrisReeves12@yahoo.com>
 **/

import React from 'react';
import ReactDOM from 'react-dom';
import HomeStore from 'stores/HomeStore';
import * as SendTaktPopupActions from 'actions/SendTaktPopupActions';
import Util from 'global/Util';
import RecipientLine from './RecipientLine';

export default class SendTaktPopup extends React.Component
{
    constructor()
    {
        super();
        let state = HomeStore.getTaktRecipientData();
        state.takt_message = '';
        this.state = state;
    }

    componentDidMount()
    {
        HomeStore.on('change_takt_popup', () => {
            this.updatePopup();
        });
    }

    updatePopup()
    {
        this.setState(this.getStateFromStores());
    }

    getStateFromStores()
    {
        return HomeStore.getTaktRecipientData();
    }

    doClosePopup(e)
    {
        e.preventDefault();
        SendTaktPopupActions.closeTaktMessagePopup();
    }

    updateTaktMessageInput(e)
    {
        SendTaktPopupActions.updateTaktMessageInput(e.target.innerHTML);
    }

    renderRecipientLines()
    {
        if(Array.isArray(this.state.recipients))
        {
            return this.state.recipients.map(recipient =>  {
                return (<RecipientLine key={recipient.id} data={recipient}/>);
            });
        }
    }

    render()
    {
        return(
            <div className={'send-takt-popup-container' + (this.state.visible ? '' : ' hidden')}>
                <a onClick={this.doClosePopup.bind(this)} className="close-button" href="">
                    <i className="fa fa-times"/>
                </a>
                <div className="row">
                    <div className="col-3">
                        <h2 style={{marginLeft: 7}}>Recipients</h2>
                    </div>
                    <div className="col-8">
                        <h2>Message</h2>
                    </div>
                </div>
                <div className="row">
                    <div className="col-3 recipients">{this.renderRecipientLines()}</div>
                    <div className="col-8">
                        <div onInput={this.updateTaktMessageInput.bind(this)}
                             className="input-area" contentEditable={true}/>
                        <div className="button-bar">
                            <button className="send-takt-button">
                                <img src={Util.staticAssets('img/layout/taktyx_icon1.png')}/>
                                Send Takt {Array.isArray(this.state.recipients) && this.state.recipients.length > 1
                                ? 'Messages' : 'Message'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

    static initialize()
    {
        let elements = document.getElementsByClassName('send-takt-popup');
        for(let x = 0; x < elements.length; x++)
        {
            let element = elements[x];
            ReactDOM.render(<SendTaktPopup/>, element);
        }
    }
}
