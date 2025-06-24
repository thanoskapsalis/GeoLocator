import {useEffect, useState} from "@wordpress/element";
import {Button, DatePicker, DateTimePicker, Flex, FlexBlock, PanelBody, TextControl} from "@wordpress/components";
import {Grid, GridColumn as Column} from "@progress/kendo-react-grid";
import apiFetch from "@wordpress/api-fetch";

export const ManagerPage = () => {

    const [formData, setFormData] = useState({})
    const [saving, setSaving] = useState(false);
    const [date, setDate] = useState(new Date());
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
        apiFetch({
            path:'/geolocator/api/data', 
            method: 'POST', 
            headers: {'Content-Type': 'application/json'},
            data: formData})
            .then(response => {
                setFormData({});
                setData(response.data)
            })
            .catch(error => {
                console.error('API Error:', error);
            })
            .finally(() => {
                setSaving(false);
            });
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
                    variant="primary"
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
                        dataItemKey="id"
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
                        <Column field="created_at" title="Created at" editor="dateTime" width="150px" />
    
                    </Grid>
                </FlexBlock>
            </Flex>
        </>

    );
}