import 'global/PrototypeDeclarations';
import HomeSearchForm from 'components/home_search_form/HomeSearchForm';
import HomeSearchFormOptions from 'components/home_search_form_options/HomeSearchFormOptions';
import HomeSearchResults from 'components/home_search_results/HomeSearchResults';
import HomeLocationDialog from 'components/home_location_dialog/HomeLocationDialog';
import SendTaktPopup from 'components/send_takt_popup/SendTaktPopup';
import HomePageEvents from 'page_events/HomePageEvents';

HomeSearchForm.initialize();
HomeSearchFormOptions.initialize();
HomeSearchResults.initialize();
HomeLocationDialog.initialize();
SendTaktPopup.initialize();
HomePageEvents.initialize();