<html>

	<head>
		<title>订单列表</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	</head>
	<body>
		<table border="1px">
			<tr>
				<th colspan="25" style="height: 100px;font-size: 20px;">订单列表</th>
			</tr>
			<tr>
				<th colspan="25">导出时间:{$now_data}</th>
			</tr>

			<tr>
				<th style="width: 170px;">订单号</th>
				<th style="width:250px!important;" width="250px">商品信息</th>
				<th style="width: 75px;">实际付款</th>
				<th style="width: 75px;">订单状态</th>
				<th style="width: 75px;">支付方式</th>
				<th style="width: 75px;">发货方式</th>
				<th style="width: 75px;">订单类型</th>
				<th style="width: 75px;">用户ID</th>
				<th style="width: 75px;">用户</th>
				<th>平台</th>
				<th>下单时间</th>
			</tr>
			{foreach from=$order item=item name=f1}
				<tr>

					<td style="mso-number-format:'\@';">
						{$item->sNo}
					</td>
					<td class="tbText" style="width:250px!important;text-align: left;" width="250px">
						{foreach from=$item->products item=item2 name=f2}
							<span style="text-align: left;">{$item2->product_title}</span>
						{/foreach}
					</td>
					<td>
						<div>&yen;{$item->z_price}</div>
					</td>
					<td>

						<div>
							<span style="">{$item->status}</span>
						</div>
					</td>
					<td>
						<div>
								<span>
									{if $item->pay == 'wxPay'}微信支付
									{elseif $item->pay == 'wallet_Pay'}余额支付
									{elseif $item->pay == 'consumer_pay'}消费金支付
									{else}组合支付{/if}
								</span>
						</div>
					</td>
					<td>
						<div>
							<span >快递发送</span>
						</div>
					</td>
					<td>
						<div>
							<span >{if $item->otype == 'pt'}拼团订单{else}普通订单{/if}</span>
						</div>
					</td>
					<td>
						<div  class="goods-name">
							<span >{$item->user_id}</span>
						</div>
					</td>
					<td>
						<div  class="goods-name">
							<span >{$item->user_name}</span>
						</div>
					</td>
					<td>
						<div style="text-align: center;">
							<span >{if $item->source == 1}小程序{else}APP{/if}</span>
						</div>
					</td>
					<td>
						<div style="text-align: center;">
							<span >{$item->add_time}</span>
						</div>
					</td>

				</tr>
			{/foreach}
		</table>
	</body>

</html>