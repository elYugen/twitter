import React from 'react';
import ReactDOM from 'react-dom';
import ReadPost from './ReadPost';

it('It should mount', () => {
  const div = document.createElement('div');
  ReactDOM.render(<ReadPost />, div);
  ReactDOM.unmountComponentAtNode(div);
});