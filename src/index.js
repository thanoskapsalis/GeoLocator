import domReady from "@wordpress/dom-ready";
import { createRoot } from '@wordpress/element';
import { useState } from '@wordpress/element';
import {Button, Flex, FlexBlock, FlexItem, PanelBody, TextControl} from '@wordpress/components'
import {Grid,  GridColumn as Column,} from "@progress/kendo-react-grid";
import "@progress/kendo-theme-default/dist/all.css"
import {ManagerPage} from "./pages/ManagerPage";

domReady(() => {
    const root = createRoot(document.getElementById('manager-page'));
    root.render(<ManagerPage></ManagerPage>)
})