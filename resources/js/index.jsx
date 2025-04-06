import React from 'react';
import ReactDOM from 'react-dom';
import App from './components/Dashboard';

if (document.getElementById('export_onespace_container')) {
    ReactDOM.render(<App />, document.getElementById('export_onespace_container'));
}
