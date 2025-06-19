import {useEffect, useState} from "@wordpress/element";
import {Button, Flex, FlexBlock, PanelBody, TextControl} from "@wordpress/components";
import {Grid, GridColumn as Column} from "@progress/kendo-react-grid";
import apiFetch from "@wordpress/api-fetch";

export const ManagerPage = () => {

    const [thanos, setThanos] = useState('');
    const [formData, setFormData] = useState({})
    const [saving, setSaving] = useState(false);
    const [data, setData] = useState([]);

    useEffect(() => {
        apiFetch({path:'/geolocator/api/data'})
            .then(response => {
               setData(response);
            })
            .catch(error => {
                console.error('API Error:', error);
                setError(error.message || 'Something went wrong');
            });
    }, []);

    const onToggle = () => {
        setSaving(true);
        console.log(formData);
        setTimeout(function(){
            console.log("THIS IS");
        }, 2000);
        setSaving(false);
    }

    const handleChange = (field) => (value) => {
        setFormData((prev) => ({
            ...prev,
            [field]: value,
        }));
    };



    return (
        <>
            <PanelBody title="Manage data">
                <TextControl
                    label="Name"
                    onChange={handleChange('name')}
                ></TextControl>
                <TextControl
                    label="Description"
                    onChange={handleChange('description')}
                ></TextControl>
                <Button
                    isPrimary
                    isBusy={saving}
                    onClick={onToggle}
                >
                    Save
                </Button>
            </PanelBody>
            <Flex>
                <FlexBlock>
                    <Grid
                        style={{ height: '475px' }}
                        data={data}
                        dataItemKey="ProductID"
                        autoProcessData={true}
                        sortable={true}
                        pageable={true}
                        filterable={true}
                        editable={{ mode: 'incell' }}
                        defaultSkip={0}
                        defaultTake={10}
                    >
                        <Column field="id" title="ID" editable={false} filterable={false} width="75px" />
                        <Column field="name" title="Name" editor="text" />
                        <Column field="description" title="Category" editable={false} width="200px"></Column>
                        <Column field="current_date" title="Price" editor="dateTime" width="150px" />
    
                    </Grid>
                </FlexBlock>
            </Flex>
        </>

    );
}