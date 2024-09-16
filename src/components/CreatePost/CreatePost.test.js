import React from 'react';
import ReactDOM from 'react-dom';
import CreatePost from './CreatePost';

it('It should mount', () => {
  const div = document.createElement('div');
  ReactDOM.render(<CreatePost />, div);
  ReactDOM.unmountComponentAtNode(div);
});