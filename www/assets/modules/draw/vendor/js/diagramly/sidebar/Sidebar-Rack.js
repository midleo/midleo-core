(function()
{
	Sidebar.prototype.addRackPalette = function(rack, dir)
	{
		for (var i = 0; i < rack.length; i++)
		{
			if (rack[i].toLowerCase() === 'general')
			{
				this.setCurrentSearchEntryLibrary('rack', 'rackGeneral');
				this.addRackGeneralPalette();
			}
			else if (rack[i].toLowerCase() === 'f5')
			{
				this.setCurrentSearchEntryLibrary('rack', 'rackF5');
				this.addRackF5Palette();
			}
			else if (rack[i].toLowerCase() === 'dell')
			{
				this.setCurrentSearchEntryLibrary('rack', 'rackDell');
				this.addRackDellPalette();
			}
			else
			{
				this.setCurrentSearchEntryLibrary('rack', 'rack' + rack[i]);
				this.addStencilPalette('rack' + rack[i], 'Rack / ' + rack[i],
					dir + '/rack/' + rack[i].toLowerCase() + '.xml',
					';html=1;labelPosition=right;align=left;spacingLeft=15;dashed=0;shadow=0;fillColor=#ffffff;',	
					null, null, null, null, null, 'rack');
			}
		}
		
		this.setCurrentSearchEntryLibrary();
	}
	
	// Adds Rack shapes
	Sidebar.prototype.addRackGeneralPalette = function()
	{
		var s = 'strokeColor=#666666;html=1;verticalLabelPosition=bottom;labelBackgroundColor=#ffffff;verticalAlign=top;outlineConnect=0;shadow=0;dashed=0;';
		var sr = 'strokeColor=#666666;html=1;labelPosition=right;align=left;spacingLeft=15;shadow=0;dashed=0;outlineConnect=0;';
		
		//default tags
		var dt = 'rack equipment general ';
		
		this.addPaletteFunctions('rackGeneral', 'Rack / General', false,
		[
			this.createVertexTemplateEntry(s + 'shape=mxgraph.rackGeneral.container;fillColor2=#f4f4f4;container=1;collapsible=0;childLayout=rack;allowGaps=1;marginLeft=9;marginRight=9;marginTop=21;marginBottom=22;textColor=#666666;numDisp=off;', 180, 228.6, '', 'Rack Cabinet', null, null, dt + 'cabinet'),
			this.createVertexTemplateEntry(s + 'shape=mxgraph.rackGeneral.container;fillColor2=#f4f4f4;container=1;collapsible=0;childLayout=rack;allowGaps=1;marginLeft=33;marginRight=9;marginTop=21;marginBottom=22;textColor=#666666;numDisp=ascend;', 210, 228.6, '', 'Numbered Rack Cabinet', null, null, dt + 'cabinet numbered'),
			this.createVertexTemplateEntry(s + 'shape=mxgraph.rackGeneral.container;container=1;collapsible=0;childLayout=rack;allowGaps=1;marginLeft=9;marginRight=9;marginTop=21;marginBottom=22;textColor=#666666;numDisp=off;', 180, 228.6, '', 'Rack Cabinet', null, null, dt + 'cabinet'),
			this.createVertexTemplateEntry(s + 'shape=mxgraph.rackGeneral.container;container=1;collapsible=0;childLayout=rack;allowGaps=1;marginLeft=33;marginRight=9;marginTop=21;marginBottom=22;textColor=#666666;numDisp=ascend;', 210, 228.6, '', 'Numbered Rack Cabinet', null, null, dt + 'cabinet numbered'),
			this.createVertexTemplateEntry(sr + 'text;', 160, 15, '', 'Spacing', null, null, dt + 'spacing'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rackGeneral.plate;fillColor=#e8e8e8;', 160, 15, '', 'Cover Plate', null, null, dt + 'cover plate'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.general.1u_rack_server;', 160, 15, '', 'Server', null, null, dt + 'server'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rackGeneral.horCableDuct;', 160, 15, '', 'Horizontal Cable Duct', null, null, dt + 'horizontal cable duct'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rackGeneral.horRoutingBank;', 160, 20, '', 'Horizontal Routing Bank', null, null, dt + 'horizontal routing bank'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rackGeneral.neatPatch;', 160, 30, '', 'Neat-Patch', null, null, dt + 'neat patch'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rackGeneral.shelf;container=1;collapsible=0', 160, 15, '', 'Shelf', null, null, dt + 'shelf'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rackGeneral.channelBase;', 200, 30, '', 'Channel Base', null, null, dt + 'channel base'),
			this.createVertexTemplateEntry('shape=mxgraph.rackGeneral.cabinetLeg;html=1;shadow=0;dashed=0;fillColor=#444444;strokeColor=#444444;verticalLabelPosition=bottom;labelBackgroundColor=#ffffff;verticalAlign=top;', 50, 50, '', 'Cabinet Leg', null, null, dt + 'cabinet leg support'),

			//stencils
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.general.cat5e_enhanced_patch_panel_48_ports;', 160, 30, '', 'CAT5e Enhanced Patch Panel 48 ports', null, null, dt + 'cat5e enhanced patch panel port'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.general.cat5e_rack_mount_patch_panel_24_ports;', 160, 15, '', 'CAT5e Rack Mount Patch Panel 24 ports', null, null, dt + 'cat5e mount patch panel port'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.general.cat5e_rack_mount_patch_panel_96_ports;', 160, 60, '', 'CAT5e Rack Mount Patch Panel 96 ports', null, null, dt + 'cat5e mount patch panel port'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.general.hub;', 160, 30, '', 'Hub', null, null, dt + 'hub'),
			this.createVertexTemplateEntry(s + 'shape=mxgraph.rack.general.server_1;', 73, 150, '', 'Server 1', null, null, dt + 'server'),
			this.createVertexTemplateEntry(s + 'shape=mxgraph.rack.general.server_2;', 73, 150, '', 'Server 2', null, null, dt + 'server'),
			this.createVertexTemplateEntry(s + 'shape=mxgraph.rack.general.server_3;', 73, 150, '', 'Server 3', null, null, dt + 'server'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.general.switches_1;', 160, 30, '', 'Switches 1', null, null, dt + 'server'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.general.switches_2;', 160, 30, '', 'Switches 2', null, null, dt + 'server')
		]);
		
		this.setCurrentSearchEntryLibrary();
	};
	
	Sidebar.prototype.addRackF5Palette = function()
	{
		var sr = 'strokeColor=#666666;html=1;labelPosition=right;align=left;spacingLeft=15;shadow=0;dashed=0;outlineConnect=0;';
		
		//default tags
		var dt = 'rack equipment f5 ';

		this.addPaletteFunctions('rackF5', 'Rack / F5', false,
		[
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.arx_500;', 168, 20, '', 'ARX 500', null, null, dt + 'arx'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.arx_1000;', 168, 40, '', 'ARX 1000', null, null, dt + 'arx'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.arx_1500;', 168, 20, '', 'ARX 1500', null, null, dt + 'arx'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.arx_2000;', 168, 40, '', 'ARX 2000', null, null, dt + 'arx'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.arx_2500;', 168, 20, '', 'ARX 2500', null, null, dt + 'arx'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.arx_4000;', 168, 60, '', 'ARX 4000', null, null, dt + 'arx'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.arx_5000;', 168, 20, '', 'ARX 5000', null, null, dt + 'arx'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.arx_6000;', 168, 240, '', 'ARX 6000', null, null, dt + 'arx'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.big_ip_1600;', 168, 20, '', 'BIG-IP 1600', null, null, dt + 'big ip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.big_ip_2x00;', 168, 20, '', 'BIG-IP 2x00', null, null, dt + 'big ip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.big_ip_3600;', 168, 20, '', 'BIG-IP 3600', null, null, dt + 'big ip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.big_ip_3900;', 168, 20, '', 'BIG-IP 3900', null, null, dt + 'big ip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.big_ip_4x00;', 168, 20, '', 'BIG-IP 4x00', null, null, dt + 'big ip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.big_ip_5x00;', 168, 20, '', 'BIG-IP 5x00', null, null, dt + 'big ip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.big_ip_6900;', 168, 40, '', 'BIG-IP 6900', null, null, dt + 'big ip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.big_ip_89x0;', 168, 40, '', 'BIG-IP 89x0', null, null, dt + 'big ip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.big_ip_7x00;', 168, 40, '', 'BIG-IP 7x00', null, null, dt + 'big ip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.big_ip_10x00;', 168, 40, '', 'BIG-IP 10x00', null, null, dt + 'big ip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.big_ip_110x0;', 168, 60, '', 'BIG-IP 110x0', null, null, dt + 'big ip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.em_4000;', 168, 20, '', 'EM 4000', null, null, dt + 'big ip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.firepass_1200;', 168, 20, '', 'FirePass 1200', null, null, dt + 'big ip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.firepass_4100;', 168, 40, '', 'FirePass 4100', null, null, dt + 'big ip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.viprion_2400;', 168, 60, '', 'VIPRION 2400', null, null, dt + 'big ip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.viprion_4400;', 168, 120, '', 'VIPRION 4400', null, null, dt + 'big ip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.f5.viprion_4800;', 168, 320, '', 'VIPRION 4800', null, null, dt + 'big ip')
		]);
		
		this.setCurrentSearchEntryLibrary();
	};
	
	Sidebar.prototype.addRackDellPalette = function()
	{
		var sr = 'strokeColor=#666666;html=1;labelPosition=right;align=left;spacingLeft=15;shadow=0;dashed=0;outlineConnect=0;';
		
		//default tags
		var dt = 'rack equipment dell ';

		this.addPaletteFunctions('rackDell', 'Rack / Dell', false,
		[
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.dell_poweredge_1u;', 162, 15, '', 'PowerEdge 1U', null, null, dt + 'poweredge 1u'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.dell_poweredge_2u;', 162, 30, '', 'PowerEdge 2U', null, null, dt + 'poweredge 2u'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.dell_poweredge_4u;', 162, 60, '', 'PowerEdge 4U', null, null, dt + 'poweredge 4u'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.power_strip;', 162, 15, '', 'Power Strip', null, null, dt + 'power strip'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_630;', 162, 15, '', 'PowerEdge 630', null, null, dt + 'poweredge 630'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_c4140;', 162, 15, '', 'PowerEdge C4140', null, null, dt + 'poweredge c4140'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_m1000e_enclosure;', 162, 150, '', 'PowerEdge M1000e Enclosure', null, null, dt + 'poweredge m1000e enclosure'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_m420;', 20, 37, '', 'PowerEdge M420', null, null, dt + 'poweredge m420'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_m520;', 20, 37, '', 'PowerEdge M520', null, null, dt + 'poweredge m520'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_m610x;', 20, 37, '', 'PowerEdge M610x', null, null, dt + 'poweredge m610x'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_m620;', 20, 37, '', 'PowerEdge M620', null, null, dt + 'poweredge m620'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_m820;', 20, 143, '', 'PowerEdge M820', null, null, dt + 'poweredge m820'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_m915;', 20, 143, '', 'PowerEdge M915', null, null, dt + 'poweredge m820'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r240;', 162, 15, '', 'PowerEdge R240', null, null, dt + 'poweredge r240'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r340;', 162, 15, '', 'PowerEdge R340', null, null, dt + 'poweredge r340'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r440;', 162, 15, '', 'PowerEdge R440', null, null, dt + 'poweredge r440'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r540;', 162, 27, '', 'PowerEdge R540', null, null, dt + 'poweredge r540'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r640;', 162, 15, '', 'PowerEdge R640', null, null, dt + 'poweredge r640'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r6415;', 162, 15, '', 'PowerEdge R6415', null, null, dt + 'poweredge r6415'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r6515;', 162, 15, '', 'PowerEdge R6515', null, null, dt + 'poweredge r6515'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r6525;', 162, 15, '', 'PowerEdge R6525', null, null, dt + 'poweredge r6525'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r730;', 162, 30, '', 'PowerEdge R730', null, null, dt + 'poweredge r730'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r730xd;', 162, 30, '', 'PowerEdge R730xd', null, null, dt + 'poweredge r730xd'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r740;', 162, 30, '', 'PowerEdge R740', null, null, dt + 'poweredge r740'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r740xd;', 162, 30, '', 'PowerEdge R740xd', null, null, dt + 'poweredge r740xd'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r740xd2;', 162, 30, '', 'PowerEdge R740xd2', null, null, dt + 'poweredge r740xd2'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r7415;', 162, 30, '', 'PowerEdge R7415', null, null, dt + 'poweredge r7415'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r7425;', 162, 30, '', 'PowerEdge R7425', null, null, dt + 'poweredge r7425'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r7515;', 162, 30, '', 'PowerEdge R7515', null, null, dt + 'poweredge r7515'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r840;', 162, 30, '', 'PowerEdge R840', null, null, dt + 'poweredge r840'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_r940;', 162, 45, '', 'PowerEdge R940', null, null, dt + 'poweredge r940'),
			this.createVertexTemplateEntry(sr + 'shape=mxgraph.rack.dell.poweredge_xr2;', 162, 15, '', 'PowerEdge XR2', null, null, dt + 'poweredge xr2')
		]);
		
		this.setCurrentSearchEntryLibrary();
	};
})();